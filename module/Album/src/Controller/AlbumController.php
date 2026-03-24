<?php
namespace Album\Controller;

use Album\Entity\Album;
use Album\Entity\Author;
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

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost()->toArray());

            if ($form->isValid()) {
                $data  = $form->getData();
                $album = new Album();
                $album->setTitle($data['title']);
                $album->setArtist($data['artist']);

                if (!empty($data['authors'])) {
                    foreach ($data['authors'] as $authorData) {
                        $name = trim($authorData['name'] ?? '');
                        if ($name !== '') {
                            $author = new Author();
                            $author->setName($name);
                            $album->addAuthor($author);
                        }
                    }
                }

                $this->entityManager->persist($album);
                $this->entityManager->flush();

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
 

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost()->toArray());

            if ($form->isValid()) {
                $data = $form->getData();

                $album->setTitle($data['title']);
                $album->setArtist($data['artist']);

                
                foreach ($album->getAuthors() as $author) {
                    $album->removeAuthor($author);
                }

              
                if (!empty($data['authors'])) {
                    foreach ($data['authors'] as $authorData) {
                        $name = trim($authorData['name'] ?? '');
                        if ($name !== '') {
                            $author = new Author();
                            $author->setName($name);
                            $album->addAuthor($author);
                        }
                    }
                }

                $this->entityManager->flush();

                return $this->redirect()->toRoute('album');
            }
        }

       
        $authorsData = [];
        foreach ($album->getAuthors() as $author) {
            $authorsData[] = ['name' => $author->getName()];
        }

     
        $form->setData([
            'title'   => $album->getTitle(),
            'artist'  => $album->getArtist(),
            'authors' => $authorsData,
        ]);

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
            }
            return $this->redirect()->toRoute('album');
        }

        return new ViewModel(['album' => $album]);
    }
}

