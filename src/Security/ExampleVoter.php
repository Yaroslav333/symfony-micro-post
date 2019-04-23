<?php

namespace App\Security;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ExampleVoter implements VoterInterface
{

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token A TokenInterface instance
     * @param mixed $subject The subject to secure
     * @param array $attributes An array of attributes associated with the method being invoked
     *
     * @return int either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        $userRoles = $token->getRoles();
        foreach ($userRoles as $role) {
            if (in_array($role->getRole(), $attributes)) {
                return self::ACCESS_GRANTED;
            }
        }
        return self::ACCESS_DENIED;
    }
}