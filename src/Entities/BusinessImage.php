<?php

namespace Becee\Entities;

class BusinessImage
{
    protected $path;
    protected $uploaderName;
    protected $uploaderId;
    protected $priority;
    protected $businessId;

    public function __construct($path=null, $uploader_name=null, $uploader_id=null, $priority=null, $business_id=null)
    {
        $this->path = $path;
        $this->uploaderName = $uploader_name;
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
     * Get uploaderName.
     *
     * @return uploaderName.
     */
    public function getUploaderName()
    {
        return $this->uploaderName;
    }

    /**
     * Set uploaderName.
     *
     * @param uploaderName the value to set.
     */
    public function setUploaderName($uploaderName)
    {
        $this->uploaderName = $uploaderName;
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
