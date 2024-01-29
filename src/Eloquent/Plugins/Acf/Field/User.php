<?php

namespace HighLiuk\Eloquent\Plugins\Acf\Field;

use HighLiuk\Eloquent\Plugins\Acf\FieldInterface;
use HighLiuk\Eloquent\Model\Post;

/**
 * Class User.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class User extends BasicField implements FieldInterface
{
    /**
     * @var \HighLiuk\Eloquent\Model\User
     */
    protected $user;

    /**
     * @var \HighLiuk\Eloquent\Model\User
     */
    protected $value;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        parent::__construct($post);
        $this->user = new \HighLiuk\Eloquent\Model\User();
        $this->user->setConnection($post->getConnectionName());
    }

    /**
     * @param string $fieldName
     */
    public function process($fieldName)
    {
        $userId = $this->fetchValue($fieldName);
        $this->value = $this->user->find($userId);
    }

    /**
     * @return \HighLiuk\Eloquent\Model\User
     */
    public function get()
    {
        return $this->value;
    }
}
