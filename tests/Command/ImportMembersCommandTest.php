<?php

namespace App\Tests\Command;

use App\Entity\Member;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportMembersCommandTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testExecuteWithValidFile()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:import-members');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'name' => 'members.csv',
        ]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Successfully imported all members', $output);

        $members = $this->entityManager
            ->getRepository(Member::class)
            ->findAll();

       $this->assertCount(12, $members);

       /** @var Member $firstSavedMember */
        $firstSavedMember = $members[0];
        $this->assertEquals(\DateTimeImmutable::createFromFormat('!Y-m-d', '1999-04-28'), $firstSavedMember->getBirthDate());
        $this->assertEquals('12345678', $firstSavedMember->getMembershipNumber());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
