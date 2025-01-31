<?php

namespace HighLiuk\Eloquent\Plugins\Acf\Field;

use HighLiuk\Eloquent\Plugins\Acf\FieldInterface;
use HighLiuk\Eloquent\Model\Post;
use Illuminate\Support\Collection;

/**
 * Class Term.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Term extends BasicField implements FieldInterface
{
    /**
     * @var mixed
     */
    protected $items;

    /**
     * @var \HighLiuk\Eloquent\Model\Term
     */
    protected $term;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        parent::__construct($post);
        $this->term = new \HighLiuk\Eloquent\Model\Term();
        $this->term->setConnection($post->getConnectionName());
    }

    /**
     * @param string $fieldName
     */
    public function process($fieldName)
    {
        $value = $this->fetchValue($fieldName);
        if (is_array($value)) {
            $this->items = $this->term->whereIn('term_id', $value)->get(); // ids
        } else {
            $this->items = $this->term->find(intval($value));
        }
    }

    /**
     * @return Term|Collection
     */
    public function get()
    {
        return $this->items; // Collection or Term object
    }
}
