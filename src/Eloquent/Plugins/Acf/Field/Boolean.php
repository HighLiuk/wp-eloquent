<?php

namespace HighLiuk\Eloquent\Plugins\Acf\Field;

use HighLiuk\Eloquent\Plugins\Acf\FieldInterface;

/**
 * Class Boolean.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Boolean extends Text implements FieldInterface
{
    /**
     * @return bool
     */
    public function get()
    {
        return (bool) parent::get();
    }
}
