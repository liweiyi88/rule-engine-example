<?php

namespace App\Command;

use App\BusinessObjects\User;
use App\BusinessObjects\Withdrawal;
use App\ExpressionLanguageProviders\CustomExpressionLanguageProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class RuleExecutionCommand extends Command
{
    protected static $defaultName = 'rule:execute';

    protected function configure(): void
    {
        $this
            ->setDescription('Add a short description for your command');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        //simple usage.
        $rule = 'withdrawal.getAmount() <= 1000 and now > restrictedStartDate and now < restrictedEndDate';

        $withdrawal = new Withdrawal();
        $withdrawal->setAmount(1000);

        $expressionLanguage = new ExpressionLanguage();
        $result = $expressionLanguage->evaluate(
            $rule,
            [
                'withdrawal' => $withdrawal,
                'now' => new \DateTime('now'),
                'restrictedStartDate' => new \DateTime('2018-04-03'),
                'restrictedEndDate' => new \DateTime('2018-04-20')
            ]
        );

        if ($result) {
            $io->success('Rule passed!');
        } else {
            $io->error('Rule failed');
        }

        //or simpler
        $rule = 'withdrawalAmount <= withdrawalLimit and now > restrictedStartDate and now < restrictedEndDate';

        $expressionLanguage = new ExpressionLanguage();
        $result = $expressionLanguage->evaluate(
            $rule,
            [
                'withdrawalAmount' => $withdrawal->getAmount(),
                'withdrawalLimit' => $withdrawal->getLimit(),
                'now' => new \DateTime('now'),
                'restrictedStartDate' => new \DateTime('2018-04-03'),
                'restrictedEndDate' => new \DateTime('2018-04-20')
            ]
        );

        if ($result) {
            $io->success('Rule passed!');
        } else {
            $io->error('Rule failed');
        }

        ////////////////////////////////////////////////////

        //custom function
        $expressionLanguage = new ExpressionLanguage();
        $rule = 'admin(user) and withdrawal.getAmount() <= 1000';

        $expressionLanguage->register('admin',function() {
            return null;
        },
            function (array $options, $user) {
            return $user instanceof User && $user->getRole() === 'ADMIN';
        });

        $result = $expressionLanguage->evaluate($rule, [
            'user' => new User('USER'),
            'withdrawal' => $withdrawal
        ]);

        if ($result) {
            $io->success('Custom function rule passed!');
        } else {
            $io->error('Custom function rule failed');
        }



        /////////////////////////////////////////////////////

        //custom function providers
        $expressionLanguage = new ExpressionLanguage();
        $expressionLanguage->registerProvider(new CustomExpressionLanguageProvider());

        $rule = 'admin(user) and withdrawal.getAmount() <= 1000';

        $result = $expressionLanguage->evaluate($rule, [
            'user' => new User('ADMIN'),
            'withdrawal' => $withdrawal
        ]);

        if ($result) {
            $io->success('Custom function provider rule passed!');
        } else {
            $io->error('Custom function provider rule failed');
        }
    }
}
