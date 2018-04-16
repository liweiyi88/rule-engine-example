<?php
declare(strict_types=1);

namespace App\ExpressionLanguageProviders;

use App\BusinessObjects\User;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class CustomExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('admin', function() {
                return null;
            }, function(array $options, $user) {
                return $user instanceof User && $user->getRole() === 'ADMIN';
            })
        ];
    }
}