<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class RecipeVoter extends Voter
{
    public const EDIT = 'RECIPE_EDIT';
    public const VIEW = 'RECIPE_VIEW';
    public const CREATE = 'RECIPE_CREATE';
        public const LIST = 'RECIPE_LIST';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return 
            in_array($attribute, [self::CREATE, self::LIST]) ||
            (
                in_array($attribute, [self::EDIT, self::VIEW])
                && $subject instanceof \App\Entity\Recipe
            );
    }

    /**
     * @param Recipe|null $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $subject->getUser()->getId() === $user->getId();
                break;

            case self::VIEW:
            case self::LIST:
            case self::CREATE:

                // logic to determine if the user can VIEW
                // return true or false
                return true;
                break;
        }

        return false;
    }
}
