<?php
namespace Becee\Entities;
class Business
{

    public $name = null;
    public $id = null;
    public $categories = null;
    public $longitude = null;
    public $latitude = null;
    public $manager = null;
    public $website = null;
    public $tags = null;
    public $images = null;
    public $visits = null;
    public $comments = null;
    public $features = null;
    public $description = null;
    public $city = null;
    public $email = null;
    public $phone_number = null;
    public $price = null;
    public $address_1 = null;
    public $address_2 = null;
    public $ranks = null;

    public function __construct($data=null)
    {
        if($data !== null)
            $this->hydrate($data);
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }

    public function setRanks(array $ranks)
    {
        $this->ranks = $ranks;
    }

    public function add_features($new_features)
    {
        $this->$features = array_merge($this->$features, $new_features);
    }

    public function add_tags($new_tags)

    {
        $this->$tags = array_merge($this->$tags, $new_tags);
    }

    /**
     * Get name.
     *
     * @return name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param name the value to set.
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get id.
     *
     * @return id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param id the value to set.
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get longitude.
     *
     * @return longitude.
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set longitude.
     *
     * @param longitude the value to set.
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get latitude.
     *
     * @return latitude.
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set latitude.
     *
     * @param latitude the value to set.
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get manager.
     *
     * @return manager.
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set manager.
     *
     * @param manager the value to set.
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get website.
     *
     * @return website.
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set website.
     *
     * @param website the value to set.
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * Get tags.
     *
     * @return tags.
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags.
     *
     * @param tags the value to set.
     */
    public function setTags($tags)
    {
        if(is_string($tags))
        {
            $this->tags = explode(',', $tags);
        }
        else
        {
            $this->tags = $tags;
        }
    }

    /**
     * Get images.
     *
     * @return images.
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set images.
     *
     * @param images the value to set.
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * Get visits.
     *
     * @return visits.
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Set visits.
     *
     * @param visits the value to set.
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;
    }

    /**
     * Get comments.
     *
     * @return comments.
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set comments.
     *
     * @param comments the value to set.
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * Get features.
     *
     * @return features.
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set features.
     *
     * @param features the value to set.
     */
    public function setFeatures($features)
    {
        $this->features = $features;
    }

    /**
     * Get categories.
     *
     * @return categories.
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set categories.
     *
     * @param categories the value to set.
     */
    function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * Get description.
     *
     * @return description.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param description the value to set.
     */
    function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get city.
     *
     * @return city.
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city.
     *
     * @param city the value to set.
     */
    function setCity($city)
    {
        $this->city = $city;
    }
    /**
     * Get address_1.
     *
     * @return address_1.
     */
    public function getAddress_1()
    {
        return $this->address_1;
    }

    /**
     * Set address_1.
     *
     * @param address_1 the value to set.
     */
    function setAddress_1($address_1)
    {
        $this->address_1 = $address_1;
    }
    /**
     * Get address_2.
     *
     * @return address_2.
     */
    public function getAddress_2()
    {
        return $this->address_2;
    }

    /**
     * Set address_2.
     *
     * @param address_2 the value to set.
     */
    function setAddress_2($address_2)
    {
        $this->address_2 = $address_2;
    }
    /**
     * Get email.
     *
     * @return email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param email the value to set.
     */
    function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get phone_number.
     *
     * @return phone_number.
     */
    public function getPhone_number()
    {
        return $this->phone_number;
    }

    /**
     * Set phone_number.
     *
     * @param phone_number the value to set.
     */
    function setPhone_number($phone_number)
    {
        $this->phone_number = $phone_number;
    }

    /**
     * Get price.
     *
     * @return price.
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    /**
     * Set price.
     *
     * @param price the value to set.
     */
    function setPrice($price)
    {
        $this->price = $price;
    }
}
