<?php
namespace App\Command;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class UpdatePasswordCommand extends Command
{
    protected static $defaultName = 'app:user:update-password';
    private $entityManager;
    private $encoder;
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        string $name = null
    ) {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }
    public function configure()
    {
        $this
            ->setDescription('Update the user password in database')
            ->addArgument('username', InputArgument::REQUIRED, 'Name of the user in database')
            ->addArgument('password', InputArgument::REQUIRED, 'New password to set')
        ;
    }
    public function run(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $user = $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => $username])
        ;
        if (!$user) {
            $output->writeln(sprintf(
                '<error>There is no user called %s in database!</error>',
                $username
            ));
            return -1;
        }
        $user->setPassword($password);
        $this->entityManager->flush();
        return 0;
    }
}