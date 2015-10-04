<?php
    class SitemapGenerator
    {
        private $SITEMAP_NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';
        private $IMAGE_SITEMAP_NS = 'http://www.google.com/schemas/sitemap-images/0.9';
        private $SITEMAP_NS_XSD = 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';

        private $max_count_links = 50000;
        private $filename = './sitemap';

        public function generate(array $links)
        {
            if (count($links) < $this->max_count_links)
            {
                $map = $this->newDOMDocument();
                $url_set = $this->getUrlSetNode($map);
                foreach ($links as $link){
                    $this->getUrlNode($map, $url_set, $link);
                }

                $xml = $map->saveXML();
                file_put_contents($this->filename.'.xml', $xml);

            } else {
                $links = array_chunk($links, $this->max_count_links);

                for ($i = 0; $i < count($links); $i++){
                    $map = $this->newDOMDocument();
                    $url_set = $this->getUrlSetNode($map);
                    foreach($links[$i] as $link){
                        $this->getUrlNode($map, $url_set, $link);
                    }

                    $xml = $map->saveXML();

					$gz = gzopen('./'.$this->filename.$i.'.xml.gz', 'w9');
					gzwrite($gz, $xml);
					fclose($gz);
                }

				$base_node = $this->newDOMDocument();
				$sitemap_index_node = $this->getSitemapIndexNode($base_node);
				for ($i = 0; $i < count($links); $i++)
				{
					$this->getSitemapNode($base_node, $sitemap_index_node, SITE_URL.'/sitemap'.$i.'.xml.gz');
				}

				$xml = $base_node->saveXML();
				file_put_contents('./sitemap.xml', $xml);
            }
        }

        public function generateSiteMapForCity($fileName, array $links)
        {
            $sitePath = dirname(dirname(dirname(dirname(__FILE__))));
            $sitemapsPath = 'sitemaps';

            if(!file_exists($sitePath . '/' . $sitemapsPath)) {
                FileHelper::createFolder($sitemapsPath);
            }

            $filePath = $sitePath . '/' . $sitemapsPath . '/' . $fileName . '.xml';

            if (count($links) < $this->max_count_links)
            {

                $map = $this->newDOMDocument();
                $url_set = $this->getUrlSetNode($map);
                foreach ($links as $link){
                    $this->getUrlNode($map, $url_set, $link);
                }

                $xml = $map->saveXML();
                file_put_contents($filePath, $xml);
            } else {

                $links = array_chunk($links, $this->max_count_links);

                for ($i = 0; $i < count($links); $i++){
                    $gzFilePath = $sitePath . '/' . $sitemapsPath . '/' . $fileName.$i.'.xml.gz';

                    $map = $this->newDOMDocument();
                    $url_set = $this->getUrlSetNode($map);
                    foreach($links[$i] as $link){
                        $this->getUrlNode($map, $url_set, $link);
                    }

                    $xml = $map->saveXML();

					$gz = gzopen($gzFilePath, 'w9');
					gzwrite($gz, $xml);
					fclose($gz);
                }

				$base_node = $this->newDOMDocument();
				$sitemap_index_node = $this->getSitemapIndexNode($base_node);
				for ($i = 0; $i < count($links); $i++)
				{
					$this->getSitemapNode($base_node, $sitemap_index_node, SITE_URL . '/' . $sitemapsPath . '/' . $fileName.$i.'.xml.gz');
				}

				$xml = $base_node->saveXML();
				file_put_contents($filePath, $xml);
            }
        }

        public function generateImageSiteMap(array $links)
        {
            $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="' . $this->IMAGE_SITEMAP_NS . '" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
            foreach($links as $link) {
                $xml .=     '<url>';
                $xml .=         '<loc>' . $link['location'] . '</loc>';

                foreach($link['images'] AS $iValue) {
                    $xml .=         '<image:image>';
                    $xml .=             '<image:loc>' . $iValue . '</image:loc>';
                    $xml .=         '</image:image>';
                }

                $xml .=     '</url>';

            }

            $xml .= '</urlset>';

            file_put_contents('./image_sitemap.xml', $xml);

        }

		public function getSitemapIndexNode(DOMDocument $base_node)
		{
			$sitemap_index = $base_node->createElementNS($this->SITEMAP_NS, 'sitemapindex');
			$base_node->appendChild($sitemap_index);

			return $sitemap_index;
		}

		public function getSitemapNode(DOMDocument $base_node, DOMElement $sitemap_index_node, $sitemap_link)
		{
			$sitemap_node = $base_node->createElement('sitemap');
			$sitemap_index_node->appendChild($sitemap_node);

			$sitemap_node->appendChild($base_node->createElement('loc', $sitemap_link));
			$sitemap_node->appendChild($base_node->createElement('lastmod', date(DATE_ATOM)));
		}

        public function getUrlSetNode(DOMDocument $map){
            $url_set = $map->createElementNS($this->SITEMAP_NS, 'urlset');
            $map->appendChild($url_set);
            $url_set->setAttributeNS('http://www.w3.org/2000/xmlns/' ,
                'xmlns:xsi',
                'http://www.w3.org/2001/XMLSchema-instance');
            $url_set->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance',
                'schemaLocation',
                $this->SITEMAP_NS . ' ' . $this->SITEMAP_NS_XSD);
            return $url_set;
        }

        public function getUrlNode(DOMDocument $map, DOMElement $url_set, SitemapLink $link){
            $url_node = $map->createElementNS($this->SITEMAP_NS, 'url');
            $url_set->appendChild($url_node);
            $url_node->appendChild($map->createElementNS($this->SITEMAP_NS, 'loc', $link->url));
            $url_node->appendChild($map->createElementNS($this->SITEMAP_NS, 'changefreq', $link->changefreq));
            $url_node->appendChild($map->createElementNS($this->SITEMAP_NS, 'priority', $link->priority));
            $url_node->appendChild($map->createElementNS($this->SITEMAP_NS, 'lastmod', SitemapLink::getLastmod($this->filename.'.xml')));
        }

        public function newDOMDocument(){
            $map = new DOMDocument('1.0', 'UTF-8');
            $map->formatOutput = true;
            return $map;
        }
    }