<?php

namespace App\Model\Entity\Watch;

use App\Model\Entity;
use Doctrine;
use Kdyby\Doctrine\EntityManager;

class WatchRepository
{
	use Entity\PaginatorTrait;

	/**
	 * @var EntityManager
	 */
	private $em;
	/**
	 * @var WatchService
	 */
	private $watchService;

	public function __construct(EntityManager $em, WatchService $watchService)
	{
		$this->em = $em;
		$this->watchService = $watchService;
	}

	/**
	 * @param int $id
	 *
	 * @return Watch
	 * @throws WatchNotFoundException
	 */
	public function getById(int $id): Watch
	{
		try {
			$query = $this->em->createQueryBuilder()
							  ->select('watch')
							  ->from(Watch::class, 'watch')
							  ->andWhere('watch.id = :id')->setParameter('id', $id);

			return $query->getQuery()->getSingleResult();
		}
		catch (Doctrine\ORM\NoResultException $e) {
			throw new WatchNotFoundException($id, $e);
		}
	}

	/**
	 * @param array    $filter
	 * @param int|null $page
	 * @param null     $perPage
	 *
	 * @return Watch[]
	 * @throws WatchNotFoundException
	 */
	public function findByFilter(array $filter, int $page = NULL, $perPage = NULL)
	{
		try {
			$query = $this->em->createQueryBuilder()
							  ->select('watch')
							  ->from(Watch::class, 'watch');

			// TODO: apply filters
			$this->setPage($query, $page, $perPage);

			return $query->getQuery()->getResult();
		}
		catch (Doctrine\ORM\NoResultException $e) {
			throw new WatchNotFoundException;
		}
	}
}
