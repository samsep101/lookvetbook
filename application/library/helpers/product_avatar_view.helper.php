<?php
	class ProductAvatarViewHelper
	{
		public static function getView($product_id, $width = 300, $height = 256)
		{
			/**
			 * @var ProductManager $product_manager
			 * @var ProductModel $product
			 */
			$product_manager = ModelManagerFactory::getByName('product');
			$product = $product_manager->getOneById($product_id);

			if($product->image_id && $product->is_image_confirmed)
			{
				return $product->image->resize($width, $height)->path;
			}
			else
			{
				return '/media/images/no_image_product.png';
			}

		}
	}