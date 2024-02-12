<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Coin;
use App\Provider\CoinProvider;
use App\Utils\CoinUtils;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CoinController extends AbstractController
{        

    private $serializer;
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CoinProvider $coinProvider,
        private LoggerInterface $logger,
    )
    {

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    #[Route('/api/v1/coin/{id}', methods: ['GET'])]
    public function getCoin($id)
    {
        $db_coin = $this->entityManager->getRepository(Coin::class)->findBy(['coinId'=>$id],orderBy:['createdAt'=>'DESC']);
        $jsonContent = $this->serializer->serialize($db_coin, 'json');
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type','application/json');
        return $response ;
    }

    #[Route('/api/v1/coins', methods: ['POST'])]
    public function recieveCoins(Request $request): Response
    {
        $rawList = $request->getContent();
        
        $data = json_decode($rawList,true);
        
        foreach($data['data'] as $row){
            $coin = CoinUtils::parse($row);
            $createdAt = new \DateTime();
            $coin->setCreatedAt($createdAt);
            $this->logger->error("Message help: ". $this->serializer->serialize($coin,"json"));
            $this->entityManager->persist($coin);
        }
        $this->entityManager->flush();
        return new Response(json_encode($rawList),200,['Content-Type: application/json']);
    }

    #[Route('/api/v1/coins', methods: ['GET'], name: "_route")]
    public function getCoins(Request $request): Response
    {

        $qb = $this->entityManager->createQueryBuilder();

        $endDate = new \DateTime();
        
        $startDate = $endDate->sub(new \DateInterval('P1D'));
        $qb->select('c')
           ->from('App\Entity\Coin', 'c')
           ->orderBy('c.createdAt')
           ->setMaxResults(100);
           

        $coinRecords = $qb->getQuery()->getResult();
        $jsonContent = $this->serializer->serialize($coinRecords, 'json');
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type','application/json');
        return $response ;
    }
}