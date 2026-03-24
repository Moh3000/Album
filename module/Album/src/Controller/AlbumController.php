<?php

namespace Album\Controller;

use Album\Entity\Album;

use Album\Form\AlbumForm;
use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    private EntityManager $entityManager;
    private AlbumForm $albumForm;

    public function __construct(
        EntityManager $entityManager,
        AlbumForm $albumForm
    ) {
        $this->entityManager = $entityManager;
        $this->albumForm     = $albumForm;
    }

    public function indexAction()
    {
        $page    = (int) $this->params()->fromQuery('page', 1);
        $perPage = 5;

        $repository        = $this->entityManager->getRepository(Album::class);
        $doctrinePaginator = $repository->findPaginated($page, $perPage);

        $totalItems = count($doctrinePaginator);
        $totalPages = (int) ceil($totalItems / $perPage);
        $albums     = iterator_to_array($doctrinePaginator);

        return new ViewModel([
            'albums'      => $albums,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
        ]);
    }

    public function addAction()
    {
        $form = $this->albumForm;
        $album = new Album();
        

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost()->toArray());

            if ($form->isValid()) {
                $album = $form->getData();
               
                $this->entityManager->persist($album);
                $this->entityManager->flush();
                $flashMessenger = $this->flashMessenger();
                $flashMessenger->addMessage('Album "' . $album->getTitle() . '" was added successfully!', 'success');
                return $this->redirect()->toRoute('album');
            }
        }

        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        $id    = $this->params()->fromRoute('id', 0);
        $album = $this->entityManager->getRepository(Album::class)->find($id);

        if (!$album) {
            return $this->redirect()->toRoute('album');
        }

        $form = $this->albumForm;
        $form->bind($album);   

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost()->toArray());

            if ($form->isValid()) {
                $album = $form->getData(); 

                $this->entityManager->flush();
                $flashMessenger = $this->flashMessenger();
                $flashMessenger->addMessage('Album "' . $album->getTitle() . '" was edited successfully!', 'success');
                return $this->redirect()->toRoute('album');
            }
        }

        return new ViewModel(['form' => $form, 'album' => $album]);
    }

    public function deleteAction()
    {
        $id    = $this->params()->fromRoute('id', 0);
        $album = $this->entityManager->getRepository(Album::class)->find($id);

        if (!$album) {
            return $this->redirect()->toRoute('album');
        }

        if ($this->getRequest()->isPost()) {
            if ($this->params()->fromPost('confirm') === 'yes') {
                $this->entityManager->remove($album);
                $this->entityManager->flush();
                $flashMessenger = $this->flashMessenger();
                $flashMessenger->addMessage('Album "' . $album->getTitle() . '" was deleted successfully!', 'success');
            }
            return $this->redirect()->toRoute('album');
        }

        return new ViewModel(['album' => $album]);
    }
}
