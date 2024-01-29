<?php

namespace HighLiuk\Eloquent\Plugins\Acf\Field;

use HighLiuk\Eloquent\Plugins\Acf\FieldInterface;
use HighLiuk\Eloquent\Model\Post;

/**
 * Class Text.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Text extends BasicField implements FieldInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $field
     */
    public function process($field)
    {
        $this->value = $this->fetchValue($field);
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->value;
    }
}
