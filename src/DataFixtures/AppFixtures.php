<?php

namespace App\DataFixtures;

use App\Entity\Language;
use App\Entity\TarotCard;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    // LANGUAGES
    const LANGUAGES = [
        'PHP',
        'Javascript',
        'Ruby',
        'Python',
        'HTML5',
        'CSS',
        'French',
        'English',
        'Italian'
    ];

    // TAROT CARDS
    const TAROT_CARDS = [
        'The Fool' => [
            'number' => "0",
            'description' => "The Fool - Free Spirit",
        ],
        'The Magician' => [
            'number' => "1",
            'description' => "The Magician - Manifester",
        ],
        'The High Priestess' => [
            'number' => "2",
            'description' => "The High Priestess - The Wisdom within",
        ],
        'The Empress' => [
            'number' => "3",
            'description' => "The Empress - Fertility",
        ],
        'The Emperor' => [
            'number' => "4",
            'description' => "The Emperor - Structure",
        ]
    ];

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $faker->seed(1234);

        // Some Languages
        foreach (self::LANGUAGES as $key => $languageName) {
            $language = new Language();
            $language->setLanguage($languageName);
            $manager->persist($language);
            $this->addReference('language_' . $key, $language);
        }

        // Multiple Users
        for($i = 0 ; $i < 10; $i++) {
            $user = new User();
            $user->setPseudo($faker->numerify($faker->firstName().'##'));
            $user->setEmail($faker->email());
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password55'));
            $user->addLanguage($this->getReference('language_' . $faker->numberBetween(0, 8)));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            //$this->addReference('author_' . $i, $user);
        }

        // 5 Tarot Cards (for more cards, see scroll down)
        /*$i = 0;
        foreach (self::TAROT_CARDS as $cardName => $data) {
            $tarotCard = new TarotCard();
            $tarotCard->setName($cardName);
            $tarotCard->setNumber($data['number']);
            $tarotCard->setDescription($data['description']);
            $manager->persist($tarotCard);
            $i++;
        }*/

        // 20 TAROT CARDS (non-sense data)
        /*for($i = 0 ; $i < 20; $i++) {
            $tarotCard = new TarotCard();
            $tarotCard->setName($faker->name());
            $tarotCard->setNumber($faker->numberBetween(0,22));
            $tarotCard->setDescription($faker->sentence());
            $manager->persist($tarotCard);
        }*/

        $manager->flush();
    }
}
