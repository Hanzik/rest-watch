<?php

namespace App\Model\Entity\Watch;

use App;
use App\Model\Entity;
use Doctrine;
use Kdyby\Doctrine\EntityManager;

/**
 * @property App\Model\Filter\IFilter $filter
 */
class WatchRepository
{
	use Entity\FilterTrait;
	use Entity\PaginationTrait;

	/**
	 * @var EntityManager
	 */
	private $em;
	/**
	 * @var Entity\Fountain\FountainService
	 */
	private $fountainService;
	/**
	 * @var WatchService
	 */
	private $watchService;

	public function __construct(
		EntityManager $em,
		Entity\Fountain\FountainService $fountainService,
		WatchService $watchService,
		App\Model\Filter\WatchFilter $filter
	)
	{
		$this->em = $em;
		$this->fountainService = $fountainService;
		$this->watchService = $watchService;
		$this->filter = $filter;
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
							  ->andWhere('watch.id = :id')->setParameter('id', $id)
							  ->andWhere('watch.dateRemoved IS NULL');

			return $query->getQuery()->getSingleResult();
		}
		catch (Doctrine\ORM\NoResultException $e) {
			throw new WatchNotFoundException;
		}
	}

	/**
	 * @param array    $filter
	 * @param int|null $page
	 * @param int|null $perPage
	 *
	 * @return Watch[]
	 * @throws WatchNotFoundException
	 */
	public function findByFilter(array $filter, ?int $page, ?int $perPage): array
	{
		try {
			$query = $this->em->createQueryBuilder()
							  ->select('watch')
							  ->from(Watch::class, 'watch')
							  ->andWhere('watch.dateRemoved IS NULL');

			$this->applyFilters($query, 'watch', $filter);
			$this->setPage($query, $page, $perPage);

			return $query->getQuery()->getResult();
		}
		catch (Doctrine\ORM\NoResultException $e) {
			throw new WatchNotFoundException;
		}
	}

	//////////////////////
	/// write

	public function create(App\Model\DTO\WatchDTO $watchDTO, App\Model\DTO\FountainDTO $fountainDTO)
	{
		$this->em->beginTransaction();
		try {
			$fountain = $this->fountainService->create($fountainDTO);
			$watch = $this->watchService->create($watchDTO, $fountain);

			$this->em->persist($fountain);
			$this->em->persist($watch);
			$this->em->flush();

			$this->em->commit();

			return $watch;
		}
		catch (\Exception $e) {
			$this->em->rollback();
			throw $e;
		}
	}

	public function update(App\Model\DTO\WatchDTO $updatedWatch, Watch $oldWatch)
	{
		$this->em->beginTransaction();
		try {
			$watch = $this->watchService->update($updatedWatch, $oldWatch);

			$this->em->persist($watch);
			$this->em->flush();

			$this->em->commit();

			return $watch;
		}
		catch (\Exception $e) {
			$this->em->rollback();
			throw $e;
		}
	}

	public function delete(Watch $watch)
	{
		$this->em->beginTransaction();
		try {
			$watch->setDateRemoved();

			$this->em->flush($watch);

			$this->em->commit();

			return $watch;
		}
		catch (\Exception $e) {
			$this->em->rollback();
			throw $e;
		}
	}
}
