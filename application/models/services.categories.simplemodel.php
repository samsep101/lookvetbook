<?php

class ServicesCategoriesSimpleModel extends SimpleModel {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    protected $table = 'services_categories';
    protected $m2m_clinics = 'services_to_clinic';

    public function getTree() {

        $q = $this->replace([
            '{services}' => $this->table,
            '{relations}' => $this->m2m_clinics,
            '{city}' => $this->cityID,
        ], 'SELECT sc.id, slug, sc.name, parent_id, id_district, id_area, id_metro, id_street, price, SUM(IF(c.city_id = {city}, 1, 0)) as total
                FROM `{services}` sc
                LEFT JOIN `{relations}` s2c ON sc.id = s2c.services_categories_id
                LEFT JOIN `clinic` c ON s2c.clinic_id = c.id
                WHERE sc.status = 1
                GROUP BY sc.id
                ORDER BY parent_id ASC, name ASC');

        $rows = $this->db->query($q);

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
		], 'SELECT id, slug, name, genitive_name, parent_id FROM {table} WHERE slug = {slug} LIMIT 1');

		return $this->db->get($q);
    }

    public function getClinicsCount($services_id) {

        return $this->total($this->m2m_clinics, '`services_categories_id` = ' . (int)$services_id);
    }

}

/* END CLASS: ServicesModel extends SimpleModel */