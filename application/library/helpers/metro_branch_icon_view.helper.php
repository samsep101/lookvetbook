<?php
    class MetroBranchIconViewHelper {

        public static  function getImage($metro_branch)
        {
            $image_path = '';
            $image = '';

	        if ($metro_branch->image)
	        {
		        $image_path = $metro_branch->image->crop(17,14)->path;
	        }
            if ($image_path)
                $image ='<img src="'.$image_path.'" alt="'.$metro_branch->name.'">';
            return $image;
        }
    }