<?php
/**
 * Show the ID of Locations taxonomy in dashboard.
 */
namespace include\admin;

class ShowTaxonomyID
{
    /*
     *  Add the custom ID column to the location taxonomy table
     *
     * @param array $columns
     *
     * @return array
     */
    public function add_id_column($columns)
    {
        $columns['my_term_id'] = 'ID';
        return $columns;
    }
    /**
     * Populate the custom ID column with the term ID.
     *
     * @param string $content The content to display in the column.
     * @param string $column_name The name of the current column.
     * @param int $term_id The ID of the current term.
     * @return string The term ID or the existing content.
     */
    public function populate_id_column($content, $column_name, $term_id)
    {
        if ('my_term_id' === $column_name) {
            $content = $term_id;
        }
        return $content;
    }
}
