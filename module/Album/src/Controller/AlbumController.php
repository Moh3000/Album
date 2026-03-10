<?php

namespace Album\Controller;

use Album\Entity\Album;
use Album\Form\AlbumForm;
use Album\Form\AlbumFilter;
use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $page = (int) $this->params()->fromQuery('page', 1);
        $perPage = 5;

        $repository = $this->entityManager->getRepository(Album::class);
        $doctrinePaginator = $repository->findPaginated($page, $perPage);

        $totalItems = count($doctrinePaginator);
        $totalPages = (int) ceil($totalItems / $perPage);

        $albums = iterator_to_array($doctrinePaginator);

        return new ViewModel([
            'albums'      => $albums,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
        ]);
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->setInputFilter(new AlbumFilter());

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $album = new Album();
                $album->setTitle($data['title']);
                $album->setArtist($data['artist']);

                $this->entityManager->persist($album);
                $this->entityManager->flush();

                $flashMessenger = $this->flashMessenger();
                $flashMessenger->addMessage('Album "' . $data['title'] . '" was added successfully!', 'success');

                return $this->redirect()->toRoute('album');
            }
        }

        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $album = $this->entityManager->getRepository(Album::class)->find($id);

        if (!$album) {
            return $this->redirect()->toRoute('album');
        }

        $form = new AlbumForm();
        $form->setInputFilter(new AlbumFilter());

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $album->setTitle($data['title']);
                $album->setArtist($data['artist']);

                $this->entityManager->flush();

                $flashMessenger = $this->flashMessenger();
                $flashMessenger->addMessage('Album "' . $data['title'] . '" was updated successfully!', 'success');

                return $this->redirect()->toRoute('album');
            }
        }

        $form->setData([
            'title'  => $album->getTitle(),
            'artist' => $album->getArtist(),
        ]);

        return new ViewModel(['form' => $form, 'album' => $album]);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $album = $this->entityManager->getRepository(Album::class)->find($id);

        if (!$album) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $confirm = $this->params()->fromPost('confirm');

            if ($confirm === 'yes') {
                $title = $album->getTitle();
                $this->entityManager->remove($album);
                $this->entityManager->flush();

                $flashMessenger = $this->flashMessenger();
                $flashMessenger->addMessage('Album "' . $title . '" was deleted successfully!', 'success');
            }

            return $this->redirect()->toRoute('album');
        }

        return new ViewModel(['album' => $album]);
    }
}
