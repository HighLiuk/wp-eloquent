<?php

namespace HighLiuk\Eloquent\Model\Builder;

/**
 * Class TaxonomyBuilder
 *
 * @package Corcel
 * @author Junior Grossi <juniorgro@gmail.com>
 * @author Yoram de Langen <yoramdelangen@gmail.com>
 * @author HighLiuk <hello@highliuk.fr>
 * @author Thomas Georgel <thomas@hydrat.agency>
 */
class TaxonomyBuilder extends Builder
{
    /**
     * @return TaxonomyBuilder
     */
    public function category()
    {
        return $this->where('taxonomy', 'category');
    }

    /**
     * @return TaxonomyBuilder
     */
    public function menu()
    {
        return $this->where('taxonomy', 'nav_menu');
    }

    /**
     * @param string $name
     * @return TaxonomyBuilder
     */
    public function name($name)
    {
        return $this->where('taxonomy', $name);
    }

    /**
     * @param string $slug
     * @return TaxonomyBuilder
     */
    public function slug($slug = null)
    {
        if (!is_null($slug) && !empty($slug)) {
            return $this->whereHas('term', function ($query) use ($slug) {
                $query->where('slug', $slug);
            });
        }

        return $this;
    }

    /**
     * @param null $slug
     * @return TaxonomyBuilder
     */
    public function term($slug = null)
    {
        return $this->slug($slug);
    }
}