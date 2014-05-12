<?php

namespace Becee\Entities;

class BusinessComment
{
    protected $comment;
    protected $userName;
    protected $userId;
    protected $userAvatar;
    protected $pubDate;
    protected $votePos;
    protected $voteNeg;
    protected $id;
    protected $imagePath;
    protected $userCategory;


    public function __construct($comment='', $user_name=array('firstname' => 'Anonymous', 'lastname' => ''), $user_id=0, $user_avatar=null, $pub_date=0, $vote_pos=0, $vote_neg=0, $id='', $image_path=null, $user_category=null)
    {
        $this->setComment($comment);
        $this->setUserName($user_name);
        $this->setUserId($user_id);
        $this->setUserAvatar($user_avatar);
        $this->setPubDate($pub_date);
        $this->setVotePos($vote_pos);
        $this->setVoteNeg($vote_neg);
        $this->setId($id);
        $this->setImagePath($image_path);
        $this->setUserCategory($user_category);
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

    public function getTotalVotes()
    {
        return $this->getVotePos() + $this->getVoteNeg();
    }

    /**
     * Get comment.
     *
     * @return comment.
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set comment.
     *
     * @param comment the value to set.
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get userName.
     *
     * @return userName.
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userName.
     *
     * @param userName the value to set.
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Get userId.
     *
     * @return userId.
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userId.
     *
     * @param userId the value to set.
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get userAvatar.
     *
     * @return userAvatar.
     */
    public function getUserAvatar()
    {
        return $this->userAvatar;
    }

    /**
     * Set userAvatar.
     *
     * @param userAvatar the value to set.
     */
    public function setUserAvatar($userAvatar)
    {
        $this->userAvatar = $userAvatar;
    }

    /**
     * Get pubDate.
     *
     * @return pubDate.
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * Set pubDate.
     *
     * @param pubDate the value to set.
     */
    public function setPubDate($pubDate)
    {
        $this->pubDate = $pubDate;
    }

    /**
     * Get votePos.
     *
     * @return votePos.
     */
    public function getVotePos()
    {
        return $this->votePos;
    }

    /**
     * Set votePos.
     *
     * @param votePos the value to set.
     */
    public function setVotePos($votePos)
    {
        if($this->votePos !== null)
        {
            $this->votePos = $votePos;
        }
        else
        {
            $this->votePos = 0;
        }
    }

    /**
     * Get voteNeg.
     *
     * @return voteNeg.
     */
    public function getVoteNeg()
    {
        return $this->voteNeg;
    }

    /**
     * Set voteNeg.
     *
     * @param voteNeg the value to set.
     */
    public function setVoteNeg($voteNeg)
    {
        if($this->voteNeg !== null)
        {
            $this->voteNeg = $voteNeg;
        }
        else
        {
            $this->voteNeg = 0;
        }
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
      * Get imagePath.
      *
      * @return imagePath.
      */
     public function getImagePath()
     {
         return $this->imagePath;
     }

    /**
     * Set imagePath.
     *
     * @param imagePath the value to set.
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    }

     /**
      * Get userCategory.
      *
      * @return userCategory.
      */
     public function getUserCategory()
     {
         return $this->userCategory;
     }

    /**
     * Set userCategory.
     *
     * @param userCategory the value to set.
     */
    public function setUserCategory($userCategory)
    {
        $this->userCategory = $userCategory;
    }
}