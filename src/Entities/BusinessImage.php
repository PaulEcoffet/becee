<?php

namespace Becee\Entities;

class BusinessImage
{
    protected $path;
    protected $uploaderFirstName;
    protected $uploaderLastName;
    protected $uploaderId;
    protected $priority;
    protected $businessId;

    public function __construct($path=null, $uploader_firstname=null, $uploader_lastname=null, $uploader_id=null, $priority=null, $business_id=null)
    {
        $this->path = $path;
        $this->uploaderFirstName = $uploader_firstname;
        $this->uploaderLastName = $uploader_lastname;
        $this->uploaderId = $uploader_id;
        $this->priority = $priority;
        $this->businessId = $business_id;
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

    /**
     * Get path.
     *
     * @return path.
     */
    public function getPath()
    {
        return $this->path;
    }


    /**
     * Set path.
     *
     * @param path the value to set.
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get uploaderFirstName.
     *
     * @return uploaderFirstName.
     */
    public function getUploaderFirstName()
    {
        return $this->uploaderFirstName;
    }

    /**
     * Set uploaderFirstName.
     *
     * @param uploaderFirstName the value to set.
     */
    public function setUploaderFirstName($uploaderFirstName)
    {
        $this->uploaderFirstName = $uploaderFirstName;
    }

    /**
     * Get uploaderLastName.
     *
     * @return uploaderLastName.
     */
    public function getUploaderLastName()
    {
        return $this->uploaderLastName;
    }

    /**
     * Set uploaderLastName.
     *
     * @param uploaderLastName the value to set.
     */
    public function setUploaderLastName($uploaderLastName)
    {
        $this->uploaderLastName = $uploaderLastName;
    }

    /**
     * Get uploaderId.
     *
     * @return uploaderId.
     */
    public function getUploaderId()
    {
        return $this->uploaderId;
    }

    /**
     * Set uploaderId.
     *
     * @param uploaderId the value to set.
     */
    public function setUploaderId($uploaderId)
    {
        $this->uploaderId = $uploaderId;
    }

    /**
     * Get priority.
     *
     * @return priority.
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set priority.
     *
     * @param priority the value to set.
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * Get businessId.
     *
     * @return businessId.
     */
    public function getBusinessId()
    {
        return $this->businessId;
    }

    /**
     * Set businessId.
     *
     * @param businessId the value to set.
     */
    public function setBusinessId($businessId)
    {
        $this->businessId = $businessId;
    }
}
