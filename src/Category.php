<?php

namespace Crew\Unsplash;

class Category extends Endpoint
{
	private $photos = [];

	/**
	 * Retrieve the a Category object from the id specified
	 *
	 * @param  integer $id Id of the category to find
	 *
	 * @api
	 * 
	 * @return Category
	 */
	public static function find($id)
	{
		$category = json_decode(self::get("categories/{$id}")->getBody(), true);
		
		return new self($category);
	}

	/**
	 * Retrieve all the categories on a specific page.
	 * The function retrieve an ArrayObject that contain Category object.
	 * 
	 * @param  integer $page Page from which the categories need to be retrieve
	 * @param  integer $per_page Number of element in a page
	 * @return ArrayObject of Category
	 *
	 * @api
	 */
	public static function all($page = 1, $per_page = 10)
	{
		$categories = self::get("categories", ['query' => ['page' => $page, 'per_page' => $per_page]]);

		$categoriesArray = self::getArray($categories->getBody(), get_called_class());

		return new ArrayObject($categoriesArray, $categories->getHeaders());
	}

	/**
	 * Retrieve all the photos for a specific category on a specific page.
	 * The function retrieve an ArrayObject that contain Photo object.
	 * 
	 * @param  integer $page Page from which the photos need to be retrieve
	 * @param  integer $per_page Number of element in a page
	 *
	 * @api
	 * 
	 */
	public function photos($page = 1, $per_page = 10)
	{
		if (! isset($this->photos["{$page}-{$per_page}"])) {
			$photos = self::get("categories/{$this->id}/photos", ['query' => ['page' => $page, 'per_page' => $per_page]]);
		
			$this->photos["{$page}-{$per_page}"] = [
				'body' => self::getArray($photos->getBody(), __NAMESPACE__.'\\Photo'),
				'headers' => $photos->getHeaders()
			];
		}

		return new ArrayObject($this->photos["{$page}-{$per_page}"]['body'], $this->photos["{$page}-{$per_page}"]['headers']);
	}
}