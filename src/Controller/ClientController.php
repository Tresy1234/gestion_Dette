<?php
namespace App\Controller;

use App\Entity\Client;
use App\Entity\Dette;
use App\Repository\DetteRepository;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ClientController extends AbstractController
{
    private ClientRepository $clientRepository;
    private DetteRepository $detteRepository; 
    private EntityManagerInterface $entityManager; 

    public function __construct(ClientRepository $clientRepository, DetteRepository $detteRepository, EntityManagerInterface $entityManager)
    {
        $this->clientRepository = $clientRepository;
        $this->detteRepository = $detteRepository;
        $this->entityManager = $entityManager; 
    }

    #[Route('/client-liste', name: 'client.index')]
    public function index(): Response
    {
        $clients = $this->clientRepository->findAll();

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients' => $clients, 
        ]);
    }

    #[Route('/client-dette/{id}', name: 'client-dette')]
    public function show(int $id): Response
    {
        $client = $this->clientRepository->findClientById($id);
        $dettes = $this->detteRepository->findDettesByClientId($id);

        return $this->render('client/detteClient.html.twig', [
            'client' => $client,
            'dettes' => $dettes,
        ]);
    }

    #[Route('/client-create', name: 'client.create')]
    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $surname = $request->request->get('surname');
            $telephone = $request->request->get('telephone');
            $adresse = $request->request->get('adresse');

            $client = new Client();
            $client->setSurname($surname);
            $client->setTelephone($telephone);
            $client->setAdresse($adresse);

            $this->entityManager->persist($client);
            $this->entityManager->flush();

            $this->addFlash('success', 'Client ajouté avec succès!');
            
            return $this->redirectToRoute('client.index');
        }

        return $this->redirectToRoute('client.index');
    }

#[Route('/dette.create', name: 'dette.create')]
public function createDette(): Response
{
        return $this->render('client/create.html.twig', [
            'controller_name' => 'ClientController',
        ]);
}

// #[Route('/dette.create', name: 'dette.create')]
// public function createDette(Request $request, int $clientId): Response
// {
//     $client = $this->clientRepository->find($clientId);
//     if ($request->isMethod('POST')) {
//         $montant = $request->request->get('montant');
//         $montantVerser = $request->request->get('montantVerser');

//         $dette = new Dette();
//         $dette->setMontant($montant);
//         $dette->setMontantVerser($montantVerser);
//         $dette->setCreateAt(new \DateTimeImmutable());
//         $dette->setClient($client);

//         $this->entityManager->persist($dette);
//         $this->entityManager->flush();

//         $this->addFlash('success', 'Dette ajoutée avec succès!');
//         return $this->redirectToRoute('client-dette', ['id' => $clientId]);
//     }

//     return $this->render('client/create_dette.html.twig', [
//         'client' => $client,
//     ]);
// }


}
