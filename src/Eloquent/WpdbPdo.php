<?php

namespace HighLiuk\Eloquent;

/**
 * Connection Resolver
 *
 * @package HighLiuk\Eloquent
 * @author HighLiuk <hello@highliuk.fr>
 * @author Thomas Georgel <thomas@hydrat.agency>
 */
class WpdbPdo
{
    /**
     * DB Instance
     */
    protected $db;
    
    public function __construct($wpdb)
    {
        $this->db = $wpdb;
    }

    public function lastInsertId()
    {
        return $this->db->insert_id;
    }
    
    public function prefix()
    {
        return $this->db->prefix;
    }
}
