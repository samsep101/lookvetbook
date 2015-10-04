<?php
	/**
	 * @property int $id
	 * @property string $name
	 * @property string $alias
	 * @property int $metro_branch_id
	 * @property MetroBranchModel $metro_branch
	 * @property int $region_id
	 * @property RegionModel $region
	 * @property string $longitude
	 * @property string $latitude
	 * @property int $number
     * @property int $city_id
	 * @property CityModel $city
     *
	 * @property MetroBranchModel $parent
	 * @property string $seo_name
	 * @property string $name_with_city_name
	 */
	class MetroStationModel extends DynamicModel
	{
		protected function _field_parent()
		{
			return $this->region;
		}

		protected function _field_seo_name()
		{
			return 'метро ' . $this->name;
		}

		protected function _field_name_with_city_name()
		{
			if($this->metro_branch && $this->metro_branch->metro->city)
			{
				return $this->name . ' (' . $this->metro_branch->name . ', ' . $this->metro_branch->metro->city->name . ')';
			}
			else
			{
				return $this->name;
			}
		}
	}