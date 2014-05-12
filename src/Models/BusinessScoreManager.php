<?php

namespace Becee\Models;

class BusinessScoreManager
{
    private $app = null;
    private $pdo = null;

    public function __construct($app)
    {
        $this->app = $app;
        $this->pdo = $app->getPdo();
    }

    /**
     * /!\ This function is really slow!
     */
    public function compute_score()
    {
        $business_score_update_req = $this->pdo->prepare('UPDATE vm_score_businesses_features
            SET
                elo_score=:elo_score,
                rank_zone=:rank_zone,
                last_computation_date=NOW()
            WHERE
                feature_id=:feature_id AND business_id=:business_id;');

        $businesses_current_elo_req = $this->pdo->prepare('SELECT * FROM vm_score_businesses_features ORDER BY feature_id, business_id');
        $businesses_current_elo_req->execute();
        $businesses_current_elo = $businesses_current_elo_req->fetchAll();

        $businesses_last_comp_req = $this->pdo->prepare('SELECT
            bv1.business_id as b1_id,
            bv2.business_id as b2_id,
            businesses_comparisons.winner,
            businesses_comparisons.score,
            businesses_comparisons.feature_id
        FROM
            businesses_comparisons
                INNER JOIN
            business_visits bv1 ON businesses_comparisons.business_visit1_id = bv1.id
                INNER JOIN
            business_visits bv2 ON businesses_comparisons.business_visit2_id = bv2.id
        WHERE date_comp >= :date
        ORDER BY feature_id;');

        $businesses_last_comp_req->execute(array('date' => $businesses_current_elo[0]['last_computation_date']));

        $businesses = array();
        if($businesses_last_comp_req->rowCount() > 0)
        {
            while($bus_last_comp = $businesses_last_comp_req->fetch())
            {
                $id_b1 = $bus_last_comp['b1_id'];
                $id_b2 = $bus_last_comp['b2_id'];
                $feature_id = $bus_last_comp['feature_id'];
                if(!isset($businesses[$feature_id]))
                {
                    $businesses[$feature_id] = array();
                }
                if(!isset($businesses[$feature_id][$id_b1]))
                {
                    $businesses[$feature_id][$id_b1]['score'] = 0;
                }
                if(!isset($businesses[$feature_id][$id_b2]))
                {
                    $businesses[$feature_id][$id_b2]['score'] = 0;
                }
                $businesses[$feature_id][$id_b1]['score'] += (($id_b1 === $bus_last_comp['winner'])? 1 : -1) * $bus_last_comp['score'];
                $businesses[$feature_id][$id_b2]['score'] += (($id_b2 === $bus_last_comp['winner'])? 1 : -1) * $bus_last_comp['score'];
            }
            foreach($businesses_current_elo as $business_old_score)
            {
                $businesses[$business_old_score['business_id']][$business_old_score['feature_id']]['score'] += $business_old_score['elo_score'];
            }
            foreach($businesses as $feature_id => &$bus_list)
            {
                uasort($bus_list, '\\Becee\\Models\\cmp_desc');
                $i = 1;
                foreach($bus_list as $business_id => $business)
                {
                    $business_score_update_req->bindValue('elo_score', $business['score']);
                    $business_score_update_req->bindValue('rank_zone', $i);
                    $business_score_update_req->bindValue('feature_id', $feature_id);
                    $business_score_update_req->bindValue('business_id', $business_id);
                    $business_score_update_req->execute();
                    $i++;
                }
            }

            echo '<pre>';
            print_r($businesses);
            echo '</pre>';
        }
        else
        {
            echo 'Nothing to do<br />';
        }
    }
}

function cmp_desc($a, $b)
{
    if ($a['score'] == $b['score'])
    {
        return 0;
    }
    return ($a['score'] > $b['score']) ? -1 : 1;
}
