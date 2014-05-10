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
        $this->tags = $tags;
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
}
