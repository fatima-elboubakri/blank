<?php
namespace App\Controller;
use App\Entity\Product;
use App\Form\Type\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_product_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();
        return $this->render('product/product.html.twig', [
            'products' => $products,
        ]);
    }
    /**
     * @Route("/add", name="app_product_add", methods={"GET", "POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', sprintf(
                'Product %s successfully added!',
                $product->getId()
            ));
            return $this->redirectToRoute('app_product_index');
        }
        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/edit/{id}", name="app_product_edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        Product $product
    ): Response {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', sprintf(
                'Product %s successfully edited!',
                $product->getId()
            ));
            return $this->redirectToRoute('app_product_index');
        }
        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/delete/{id}", name="app_product_delete", methods={"POST"})
     */
    public function delete(
        Request $request,
        EntityManagerInterface $entityManager,
        Product $product
    ): Response {
        if ($this->isCsrfTokenValid('delete-product', $request->get('token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('success', 'Product successfully deleted!');
        } else {
            $this->addFlash('danger', 'Token is invalid!');
        }
        return $this->redirectToRoute('app_product_index');
    }

    public function export(EntityManagerInterface $entityManager, SerializerInterface $serializer ): Response
    {
        $product = $entityManager->getRepository(Product::class)->findAll();
        $export = $serializer->serialize($product, 'json',[
            'groups'=>['export']
        ]);
        return new Response($export);
    }
}