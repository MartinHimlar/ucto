<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 25. 8. 2015
 * Time: 21:12
 */

namespace Sites;


use App\CarouselExistException;
use App\CarouselNotRemoveException;
use App\NotSupportedException;
use App\NotSupportedImageSizeException;
use Nette\Http\FileUpload;
use Nette\Object;
use Nette\Utils\DateTime;

class CarouselManager extends Object
{

	/** @var CarouselRepository $repository */
	public $repository;

	public function __construct(CarouselRepository $repository)
	{
		$this->repository = $repository;
	}

	public function create(FileUpload $file, $title)
	{
		if (!($file->getContentType() == 'image/png' || $file->getContentType() == 'image/jpeg')) {
			throw new NotSupportedException('Nesprávný formát obrázku. Obrázek musí bý ve rmátu JPEG nebo PNG');
		}

		$filename = $file->getSanitizedName();
		$image = $file->toImage();

		if (!($image->width === 1140) && !($image->height === 200)) {
			throw new NotSupportedImageSizeException('Nesprávná velikost obrázku (1140 x 200).');
		}

		$row = $this->repository->find()
			->where('title', $title)
			->fetch();

		if ($row) {
			throw new CarouselExistException('Obrázek s tímto titulkem již existuje.');
		}

		$now = new DateTime();
		$filename = $now->format('dd_mm_yyyy_h_i') . '_' . $filename;

		$image->save(self::getImageFilepath() . $filename);
		$this->repository->add(array('title' => $title, 'image_filename' => $filename));
	}

	public function find()
	{
		return $this->repository->find();
	}

	/**
	 * if image exist, remove row from database and delete file
	 *
	 * @param $id
	 */
	public function remove($id)
	{
		$row = $this->repository->get($id);

		if ($row && file_exists(self::getImageFilepath() . $row->image_filename)) {
			unlink(self::getImageFilepath() . $row->image_filename);
			$this->repository->remove($id);
		} elseif ($row) {
			$this->repository->remove($id);
		} else {
			throw new CarouselNotRemoveException('Obrázek nelze smazat. Neexistuje');
		}
	}

	/**
	 * returns path to carousel image
	 *
	 * @return string
	 */
	public static function getImageFilepath()
	{
		return IMAGES_DIR . DIRECTORY_SEPARATOR . 'slides' . DIRECTORY_SEPARATOR;
	}

}