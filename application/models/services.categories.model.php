<?php

class ServicesCategoriesModel extends SimpleModel {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    protected $table = 'services_categories';

    public function getTree() {

        $rows = $this->get_where_orderby($this->table, 'status = 1', [
            'id',
            'slug',
            'name',
            'parent_id',
            'id_district',
            'id_area',
            'id_metro',
            'id_street',
            'price'
        ], 'parent_id ASC, name ASC');

        $tree = [];

        // каталог двухуровневый поэтому делаем по простому
        foreach($rows as $one){
            
            $id = $one['id'];
            $parent_id = $one['parent_id'];
            
            unset($one['id']);
            unset($one['parent_id']);

            if($parent_id > 0 AND !empty($tree[$parent_id])){

                $one['full_slug'] = $tree[$parent_id]['slug'].'/'.$one['slug'];

                $tree[$parent_id]['subslugs'][$id] = $one;

            } else {

                $one['count'] = 0;
                $one['subslugs'] = [];

                $tree[$id] = $one;
            }

        }

        foreach($tree as $id => $root){

            $root['count'] = count($root['subslugs']);
            $tree[$id] = $root;
        }

        return $tree;

    }

    public function reindexMorphy() {

        $word_decline = WordDeclination::getInstance();

        $rows = $this->get($this->table, 0, 0, ['id', 'name']);

        foreach($rows as $one){

            $name = preg_replace('#[^а-яА-Я0-9\s]+#ui', '', $one['name']);
            $name = trim($name);

            $this->update($this->table, [
                'genitive_name' => $word_decline->toGenitive($name),
            ], 'id = '.$one['id']);
        }
    }

    public function getBySlug($slug) {

        $q = str_replace(['{table}', '{slug}'], [
            $this->table,
            $this->escape($slug)
		], 'SELECT id, slug, name, genitive_name FROM {table} WHERE slug = {slug} LIMIT 1');

		return $this->db->get($q);
    }
}

/* END CLASS: ServicesModel extends SimpleModel */