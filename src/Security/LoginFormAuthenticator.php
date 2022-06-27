<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'connexion';

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        // $dbname = "archhive";
        // $dbuser = "root";
        // $dbhost = "localhost";
        // $dbpassword = "(zaOkfrac[XHy/GI";

        // try {
        //     $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);
        // } catch (PDOException $e) {
        //     die("Erreur de connexion à la base de données : " . $e->getMessage());
        // }
        // $requete=query("SELECT * FROM user WHERE email = '".$email."' AND mot_de_passe = '".$password."'");
        // $statement = $db->prepare($requete);
        // $statement->bindParam("login", $_POST['login']);
        // $statement->execute();

        return new Passport(
        new UserBadge($email),
        new PasswordCredentials($request->request->get('mot_de_passe', '')),
        [
            new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
        ]);     
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        //Après connexion, redirige l'utilisateur sur la route demandée
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        //Exemple:
        return new RedirectResponse($this->urlGenerator->generate('profile'));
        throw new \Exception('provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
