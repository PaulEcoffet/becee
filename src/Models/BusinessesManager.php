<?php

namespace Becee\Models;
use \Becee\Entities\Business;
use \Becee\Entities\BusinessImage;
use \Becee\Entities\BusinessComment;
use \Becee\CurrentUserManager;

class BusinessesManager
{
    private $pdo = NULL;
    private $app = null;
    private $cache_folder = null;

    public function __construct(\QDE\App $app)
    {
        $this->app = $app;
        $this->pdo = $this->app->getPdo();
        $this->cache_folder = realpath($app->getCachePath().'/businesses/');
    }

/* ==========================================  GET THE BUSINESS MAIN FUNCTIONS  ====================================================================================================================== */
    public function getBusinessById($business_id)
    {
        $filename = strtolower(base_convert(strval($business_id), 10, 36)).'.business.php_serialized';
        $file_path = $this->cache_folder. '/'. $filename;
        if(file_exists($file_path))
        {
            $business = unserialize(file_get_contents($file_path));
        }
        else
        {
            $business = $this->createBusinessCache($business_id);
        }

        return $business;
    }

    public function createBusinessCache($business_id)
    {
            $filename = strtolower(base_convert(strval($business_id), 10, 36)).'.business.php_serialized';
            $file_path = $this->cache_folder. '/'. $filename;
            $business = $this->getBusinessByIdFromDB($business_id, array('with_images', 'with_comments'));
            file_put_contents($file_path, serialize($business));
            return $business;
    }

    public function getBusinessByIdFromDB($business_id, $option=null)
    {
        $sql = 'SELECT businesses.name, businesses.id,
        businesses.website,
        GROUP_CONCAT(business_tags.name) as tags,
        businesses.email,
        businesses.phone_number,
        businesses.price,
        businesses.description,
        users.id as manager,
        business_addresses.line1 as address_1,
        business_addresses.line2 as adresse_2,
        cities.id as city


        FROM businesses


        LEFT OUTER JOIN link_businesses_tags
        ON link_businesses_tags.business_id = businesses.id   /* Getting all categories */
        LEFT OUTER JOIN business_tags
        ON business_tags.id = link_businesses_tags.tag_id

        INNER JOIN users
                ON businesses.manager_id = users.id  /*Getting Manager */

                INNER JOIN business_addresses
                ON business_addresses.business_id = businesses.id  /* Getting addresse */

                INNER JOIN cities
                ON business_addresses.city_id = cities.id  /* Getting cities */

                INNER JOIN provinces
                ON cities.province_id = provinces.id    /* Getting province */

                INNER JOIN countries
                ON provinces.country_id = countries.id     /* Getting country */

        WHERE businesses.id = ?
        GROUP BY businesses.id
        ;'
        ;

        $business_req = $this->pdo->prepare($sql);
        $business_req->execute(array($business_id));
        $business_result = $business_req->fetch(\PDO::FETCH_ASSOC);
        $business_result['categories'] = $this->getBusinessCategories($business_id);
        $business_result['city'] = $this->app->getManager('Location')->getCities($business_result['city']);
        $business_result['tags'] = explode(',', $business_result['tags']);
        $business = new Business($business_result);
        if(is_array($option))
        {
            if(in_array('with_images', $option))
            {
                $business->setImages($this->getBusinessImages($business_id));
            }
            if(in_array('with_comments', $option))
            {
                $business->setComments($this->getBusinessComments($business_id));
            }
        }
        return $business;
    }

     public function getBusinessImages($business_id, $limit=5, $offset=0)
    {
        $sql = 'SELECT
                business_images.path,
                COALESCE(users.firstname, \'Anonymous\') as uploaderFirstName,
                COALESCE(users.lastname, \'Anonymous\') as uploaderLastName,
                users.id as uploaderId,
                business_images.priority,
                businesses.id as businessId
            FROM
                business_images
                    INNER JOIN
                businesses ON business_images.business_id = businesses.id
                    LEFT JOIN
                users ON business_images.user_id = users.id
            WHERE
                businesses.id = :business_id
            ORDER BY priority DESC
            LIMIT :limit OFFSET :offset;';

        $images_req = $this->pdo->prepare($sql);
        $images_req->bindValue('business_id', $business_id, \PDO::PARAM_INT);
        $images_req->bindValue('limit', $limit, \PDO::PARAM_INT);
        $images_req->bindValue('offset', $offset, \PDO::PARAM_INT);
        $images_req->execute();
        $images = array();
        while($image_data = $images_req->fetch())
        {
            $image = new BusinessImage();
            $image->hydrate($image_data);
            $images[] = $image;
        }
        return $images;
    }

    /* ==========================================  GET THE BUSINESS DETAILS FUNCTIONS  ====================================================================================================================== */

    public function getBusinessMostReleventTags($business_id, $limit=5)
    {
        $sql = 'SELECT
                business_tags.id as tag_id, business_tags.name as tag_name, businesses.name as business_name
            FROM
                business_tags
                    INNER JOIN
                link_businesses_tags ON link_businesses_tags.tag_id = business_tags.id
                    INNER JOIN
                businesses ON link_businesses_tags.business_id = businesses.id
             WHERE
                link_businesses_tags.nb_yes / (link_businesses_tags.nb_no + link_businesses_tags.nb_yes) > 0.5
                    AND businesses.id = :business_id
            ORDER BY link_businesses_tags.nb_yes / (link_businesses_tags.nb_no + link_businesses_tags.nb_yes) DESC
            LIMIT :limit
            ;'
            ;


        $tags_req = $this->pdo->prepare($sql);
        $tags_req->bindValue('business_id', $business_id, \PDO::PARAM_INT);
        $tags_req->bindValue('limit', $limit, \PDO::PARAM_INT);
        $tags_req->execute();
        $tags = $tags_req->fetchAll(\PDO::FETCH_ASSOC);
        return $tags;
    }

    public function getBusinessCategories($business_id = '%')
    {
        $sql = 'SELECT
                business_categories.id as categorie_id,
                business_categories.name as categorie_name,
                business_categories.fontAwesomeIconName as categorie_icon
            FROM
                businesses

            LEFT OUTER JOIN link_businesses_categories
            ON link_businesses_categories.business_id = businesses.id   /* Getting all categories */
            LEFT OUTER JOIN business_categories
            ON business_categories.id = link_businesses_categories.category_id

            WHERE businesses.id LIKE ?
            ORDER BY categorie_name ASC
            ;'
            ;


        $categories_req = $this->pdo->prepare($sql);
        $categories_req->execute(array($business_id));
        $categories = $categories_req->fetchAll(\PDO::FETCH_ASSOC);
        return $categories;
    }

    public function getBusinessComments($business_id, $limit=10, $offset=0)
    {
        $sql = 'SELECT
                business_comments.id,
                business_comments.comment,
                pub_date as pubDate,
                business_comments.vote_neg as voteNeg,
                business_comments.vote_pos as votePos,
                business_images.path as imagePath,
                users.id as userId,
                user_categories.name as userCategory,
                COALESCE(users.firstname, \'Anonymous\') as userFirstName,
                COALESCE(users.lastname, \'\') as userLastName,
                users.avatar_path as userAvatar
            FROM
                business_comments
                    LEFT JOIN
                users ON business_comments.user_id = users.id
            LEFT OUTER JOIN user_categories
            ON user_categories.id = users.category                
            LEFT OUTER JOIN link_comments_images
            ON link_comments_images.comment_id = business_comments.id  
            LEFT OUTER JOIN business_images
            ON link_comments_images.image_id = business_images.id  
            WHERE
                business_comments.business_id = :business_id
            ORDER BY pub_date DESC
            LIMIT :limit OFFSET :offset;'
            ;

        $comments_req = $this->pdo->prepare($sql);
        $comments_req->bindValue('business_id', $business_id, \PDO::PARAM_INT);
        $comments_req->bindValue('limit', $limit, \PDO::PARAM_INT);
        $comments_req->bindValue('offset', $offset, \PDO::PARAM_INT);
        $comments_req->execute();
        $comments = array();
        while($comment_arr = $comments_req->fetch(\PDO::FETCH_ASSOC))
        {
            $comment_arr['userName'] = array('firstname' => $comment_arr['userFirstName'], 'lastname' => $comment_arr['userLastName']);
            print_r($comment_arr);
            echo "string";
            $comment = new BusinessComment();
            $comment->hydrate($comment_arr);
            $comments[] = $comment;
        }
        return $comments;
    }

    public function getAllTags()
    {
        $sql = "SELECT * FROM business_tags;
        ;
        ";
        $business_req = $this->pdo->prepare($sql);
        $business_req->execute();
        return($business_req->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function getBusinessesByCity($city_id)
    {
        $sql = "SELECT b.name, b.description, ba.line1, bi.path, bi.id
                FROM ((businesses b INNER JOIN business_addresses ba ON b.id = ba.business_id)
                    INNER JOIN cities c ON c.id = ba.city_id)
                    INNER JOIN business_images bi ON bi.business_id = b.id
                WHERE c.id =  :city_id
        ;
        ";
        $business_req = $this->pdo->prepare($sql);
        $business_req->bindValue(':city_id', $city_id,\PDO::PARAM_INT);
        $business_req->execute();
        return($business_req->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function getCities()
    {
        $business_req = $this->pdo->prepare('SELECT c.id, c.name FROM cities c;');
        $business_req->execute();
        return($business_req->fetchAll(\PDO::FETCH_ASSOC));
    }

    /* ========================================== BUSINESS CLASH  ====================================================================================================================== */

    public function getAllBattleForUser($business_id)
    {
        $manager = $this->app->getManager('currentuser');
        $user_id = $manager->getId();

        $sql = 'SELECT businesses.id, businesses.name, features.id, features.name


        ;';
    }

    public function computeScoreForFeature($business_id, $feature_id_clash)
    {
        $sql_pos = 'SELECT SUM(score) as score
                FROM businesses_comparaisons
                WHERE feature_id = :feature_id_clash
                    AND business_visit1_id = :business_id OR business_visit2_id = :business_id
                    AND winner = :business_id
                ;';                                                      /* Compute POSITIVE Score */

        $req_pos = $this->pdo->prepare($sql_pos);
        $req_pos->bindValue('feature_id_clash', $feature_id_clash);
        $req_pos->bindValue('business_id', $business_id);
        $score = $req_pos->execute();

        $data = $score->fetch();
        $score_pos = $data['score'];


        $sql_neg = 'SELECT SUM(score) as score
                FROM businesses_comparaisons
                WHERE feature_id = :feature_id_clash
                    AND business_visit1_id = :business_id OR business_visit2_id = :business_id
                    AND winner <> :business_id
                ;';                                                      /* Compute NEGATIVE Score */

        $req_neg = $this->pdo->prepare($sql_pos);
        $req_neg->bindValue('feature_id_clash', $feature_id_clash);
        $req_neg->bindValue('business_id', $business_id);
        $score2 = $req_neg->execute();

        $data2 = $score->fetch();
        $score_neg = $data2['score2'];

        return $score_pos - $score_neg;
    }


    public function businessesComparaisonByFeature($business_id1, $business_id2, $winner_id, $feature_id)
    {

        $score1 = computeScoreForFeature($business_id1, $feature_id);
        $score2 = computeScoreForFeature($business_id2, $feature_id);

        if ($business_id1 == $winner_id)
        {
            $score_final = computeEloScore($score1, $score2);
        }

        else
        {
            $score_final = computeEloScore($score2, $score1);
        }

        $add_data = 'INSERT INTO businesses_comparaisons (business_visit1_id,business_visit2_id,
                    winner, feature_id, score)
                    VALUES ( :bus1, :bus2, :win, :feat, :score)
                    ;'
                    ;

        $app_data = $this->pdo->prepare($sql);
        $add_data->bindValue('bus1', $business_id1);
        $add_data->bindValue('bus2', $business_id2);
        $add_data->bindValue('win', $winner_id);
        $add_data->bindValue('feat', $feature_id);
        $add_data->bindValue('score', $score_final);

        $add_data->execute();

    }


    public function computeEloScore($score1, $score2) //Maybe try to change the K factor 
    {

        $score = $score1 + 30*(1 - 1/(1 + (pow(10, -($score1 - $score2)))/400));
        return abs($score);
    }



     /* ========================================== BUSINESS SEARCH  ====================================================================================================================== */
    public function searchBusinesses($location, $category=null, $tags=null, $limit=20, $offset=0)
    {
        if($category === null)
        {
            $category = '%';
        }
        if(is_string($category))
        {
            $category_search = 'bc.name LIKE :category';
        }
        else
        {
            $category_search = 'bc.id = :category';
        }
        if(is_string($location))
        {
            $location_search = 'c.name LIKE :city';
        }
        else
        {
            $location_search = 'c.id = :city';
        }
        if(empty($tags))
        {
            $in_content = 'LIKE \'%\'';
            $having_content = '';
        }
        else
        {
            $having_content = 'HAVING tags_score > 0';
            $in_content = 'IN(';
            for($i = 0; $i < count($tags) - 1; $i++) // Ugly but I haven't found anything better
            {
                $in_content .= ':tag'.$i.', ';
            }
            $in_content .= ':tag' .( count($tags)-1) . ')';
        }
        $sql = 'SELECT DISTINCT
                b.id,
                COALESCE(
                            (SELECT -- We compute a score depending of the tags
                                    SUM((cast(lbt.nb_yes as signed) - cast(lbt.nb_no as signed)) / (lbt.nb_yes + lbt.nb_no))
                                FROM
                                    link_businesses_tags lbt
                                        INNER JOIN
                                    business_tags bt ON bt.id = lbt.tag_id
                                WHERE
                                    bt.name ' . $in_content . '
                                        AND lbt.business_id = b.id),
                        0) as tags_score,
                    IF(EXISTS(SELECT -- If the category of the business is good, we apply a strong bonus to it (1 point)
                            *
                        FROM
                            link_businesses_categories lbt
                                INNER JOIN
                            business_categories bc ON lbt.category_id = bc.id
                        WHERE '
                            .$category_search. '
                                AND lbt.business_id = b.id),
                    1,
                    0) as category_score
                FROM
                businesses b
                    INNER JOIN
                business_addresses ba ON b.id = ba.business_id
                    INNER JOIN
                cities c ON c.id = ba.city_id

            WHERE '. $location_search. '
            GROUP BY b.id '.
            $having_content.
            ' ORDER BY tags_score + category_score DESC
            LIMIT :limit OFFSET :offset
            ;'

            ;
        $business_req = $this->pdo->prepare($sql);
        $business_req->bindValue(':category', $category, \PDO::PARAM_STR);
        $business_req->bindValue(':city', $location, \PDO::PARAM_STR);
        $business_req->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $business_req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        if($tags !== null)
        {
            for($i = 0; $i <count($tags); $i++)
            {
                $business_req->bindValue(':tag'.$i, $tags[$i], \PDO::PARAM_STR);
            }
        }
        $business_req->execute();
        $businesses = array();
        while($record = $business_req->fetch())
        {
            $business = $this->getBusinessById($record['id']);
            $businesses[] = $business;
        }
        return($businesses);
    }

    /* ========================================== INSERT BUSINESS  ====================================================================================================================== */



    public function insertBusiness($business, $image_path=NULL)
    {
        $sql = "INSERT INTO `businesses` (name, description)
                VALUES         (:name, :description)
                ;

                SELECT         MAX(id)
                FROM         `businesses`
                INTO         @LAST_ID
                ;


                INSERT INTO `provinces` (name, country_id)
                SELECT         :province,
                            (SELECT c.id FROM countries c WHERE c.name = :country LIMIT 1)
                FROM         dual
                WHERE         NOT EXISTS
                            (SELECT 1 from `provinces` WHERE name = :province and country_id = (SELECT c.id FROM provinces p INNER JOIN countries c ON p.country_id = c.id WHERE p.name = :province AND c.name = :country LIMIT 1))
                ;


                INSERT INTO `cities` (name, province_id)
                SELECT         :city,
                            (SELECT p.id FROM provinces p INNER JOIN countries c ON p.country_id = c.id WHERE p.name = :province AND c.name = :country LIMIT 1)
                FROM         dual
                WHERE         NOT EXISTS
                            (SELECT 1 from `cities` WHERE name = :city and province_id = (SELECT p.id FROM provinces p INNER JOIN countries c ON p.country_id = c.id WHERE p.name = :province AND c.name = :country LIMIT 1))
                ;


                INSERT INTO `business_addresses` (business_id, city_id, line1, line2, lat, lng)
                VALUES         (
                            @LAST_ID,
                            (SELECT cities.id FROM (provinces p INNER JOIN countries c ON p.country_id = c.id) INNER JOIN cities ON cities.province_id = p.id WHERE p.name = :province AND c.name = :country AND cities.name = :city LIMIT 1),
                            :line1,
                            :line2,
                            :lat,
                            :lng
                            )
                ;
                ";

        $business_req = $this->pdo->prepare($sql);
        $business_req->bindValue(':city', ucwords(strtolower($business['city'])),\PDO::PARAM_STR);
        $business_req->bindValue(':name', ucwords(strtolower($business['name'])),\PDO::PARAM_STR);
        $business_req->bindValue(':description', ucfirst(strtolower($business['description'])),\PDO::PARAM_STR);
        $business_req->bindValue(':province', ucwords(strtolower($business['province'])),\PDO::PARAM_STR);
        $business_req->bindValue(':country', ucwords(strtolower($business['country'])),\PDO::PARAM_STR);
        $business_req->bindValue(':line1', ucwords(strtolower($business['line1'])),\PDO::PARAM_STR);
        $business_req->bindValue(':line2', ucwords(strtolower($business['line2'])),\PDO::PARAM_STR);
        $business_req->bindValue(':lat', ucwords(strtolower($business['lat'])),\PDO::PARAM_STR);
        $business_req->bindValue(':lng', ucwords(strtolower($business['lng'])),\PDO::PARAM_STR);
        $business_req->bindValue(':image_path', $image_path,\PDO::PARAM_STR);
        $business_req->execute();

        $sql = "SELECT id FROM `businesses` WHERE id = (SELECT MAX(id) FROM `businesses`);";

        $business_req = $this->pdo->prepare($sql);
        $business_req->execute();

        $business = $this->createBusinessCache($business_req->fetch()['id']);

        return $business;
    }

    public function insertVoteToComment($comment_id, $is_votePos)
    {
        $sql = 'UPDATE business_comments
                SET vote_pos = vote_pos + ?, vote_neg = vote_neg + ?
                WHERE id = ?
                ;
                ';

        $visit_req = $this->pdo->prepare($sql);
        $visit_req->execute(array(($is_votePos ? 1 : 0), ($is_votePos ? 0 : 1), $comment_id));
        $business = $this->createBusinessCache($business_id);
    }

    public function insertVisit($business_id)
    {
        $userManager = $app->getManager('currentuser');
        $user_id = $userManager->getId();

        $sql = 'INSERT INTO business_visits( user_id, business_id, visit_date)
                VALUES ( :user, :business , NOW())
                ;'
                ;

        $visit_req = $this->pdo->prepare($sql);
        $visit_req->bindValue(':user', $user_id);
        $visit_req->bindValue(':business', $business_id);

        $visit_req->execute();
        $business = $this->createBusinessCache($business_id);
    }

    public function insertComment($business_id, $user_id, $comment)
    {

        $sql = "INSERT INTO business_comments(user_id, business_id, comment, pub_date, status)
                VALUES (:user, :business, :comment, NOW(), 0)
                ;
                ";

        $comment_req = $this->pdo->prepare($sql);
        $comment_req->bindValue(':user', $user_id);
        $comment_req->bindValue(':business', $business_id);
        $comment_req->bindValue(':comment', $comment);
        $comment_req->execute();

        $sql = "SELECT id FROM `business_comments` WHERE id = (SELECT MAX(id) FROM `business_comments`);";

        $comment_req = $this->pdo->prepare($sql);
        $comment_req->execute();
        $business = $this->createBusinessCache($business_id);
        return $comment_req->fetch(\PDO::FETCH_ASSOC)['id'];
    }

    public function insertBusinessImage($business_id, $image_path)
    {
        $sql = "INSERT INTO `business_images` (business_id, path)
                VALUES(:business_id, :image_path)
                ;
                ";

        $business_req = $this->pdo->prepare($sql);
        $business_req->bindValue(':image_path', $image_path,\PDO::PARAM_STR);
        $business_req->bindValue(':business_id', $business_id,\PDO::PARAM_INT);
        $business_req->execute();

        $sql = "SELECT id FROM `business_images` WHERE id = (SELECT MAX(id) FROM `business_images`);";

        $business_req = $this->pdo->prepare($sql);
        $business_req->execute();
        $business = $this->createBusinessCache($business_id);
        return $business_req->fetch(\PDO::FETCH_ASSOC)['id'];
    }

    public function linkCommentWithImage($business_id, $comment_id, $image_id)
    {
        $sql = "INSERT INTO `link_comments_images` (comment_id, image_id)
                VALUES(:comment_id, :image_id)
                ;
                ";

        $business_req = $this->pdo->prepare($sql);
        $business_req->bindValue(':comment_id', $comment_id,\PDO::PARAM_INT);
        $business_req->bindValue(':image_id', $image_id,\PDO::PARAM_INT);
        $business_req->execute();
        $business = $this->createBusinessCache($business_id);
    }

}
