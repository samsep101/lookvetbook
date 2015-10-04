<?php
	class ProductShopController extends BaseController
	{
		public function get()
		{
            $this->view->menu_active = 'shop';
			//Test::dump($_SESSION);
            $product_id = $this->request('id');

            /**
             * @var ProductManager $product_manager
             * @var ProductModel $product
             * @var ProductCategoryModel $product_category
             * @var ProductCategoryModel $parent_product_category
             */
            $product_manager = ModelManagerFactory::getByName('product');

            $product = $product_manager->getOneByIdOrAlias($product_id);

            if(!isset($this->current_account) || !$this->current_account || !$this->current_account->is_product_admin)
            {
                if(!$product || !$product->is_active)
                {
                    ErrorPageViewHelper::page404();
                }
            }
            else
            {
                if(!$product)
                {
                    ErrorPageViewHelper::page404();
                }
            }

            if (is_numeric($product_id) && $product->alias)
            {
                RedirectManager::redirect301(ProductLinkViewHelper::getLink($product));
            }

            $product_category = $product->product_category;
            $parent_product_category = null;

            if ($product_category) {
                $this->view->category_id = $product->product_category->getId();
                $this->view->product_category = $product_category;

                $parent_product_category = $product_category->parent;

                if(!$parent_product_category || !$parent_product_category->parent)
                {
                    $parent_product_category = null;
                }
            }

            $this->view->product = $product;
            $this->view->product_category = $product_category;
            $this->view->parent_product_category = $parent_product_category;
            $this->view->page_title = ProductsPageTitleGeneratorHelper::productTitle($product);

            $this->render('shop/product/index');
		}
	}