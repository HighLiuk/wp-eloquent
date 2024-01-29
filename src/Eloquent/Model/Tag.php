<?php

namespace HighLiuk\Eloquent\Model;

/**
 * Tag class.
 *
 * @package HighLiuk\Eloquent\Model
 * @author Mickael Burguet <www.rundef.com>
 */
class Tag extends Taxonomy
{
    /**
     * @var string
     */
    protected $taxonomy = 'post_tag';
}
