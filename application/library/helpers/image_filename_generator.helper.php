<?php
    class ImageFilenameGeneratorHelper
    {
        public static function getFilename($src, $alias_value)
        {
            if(!$alias_value)
            {
                $filename = time() . '-' . StringGeneratorHelper::generate(10) .'.' .$src;
            } else {
                $filename = StringTransliterationHelper::translit($alias_value);

                /**
                 * @var ImageManager $image_manager
                 */
                $image_manager = ModelManagerFactory::getByName('image');

                $counter = 0;
                $temp_filename = $filename.$src;

                while ($image_manager->getOneByFilename($temp_filename)) {
                    $counter++;
                    $temp_filename = $filename.'-'.$counter .'.' .$src;
                }
                $filename = $temp_filename;
            }

            return $filename;
        }
    }