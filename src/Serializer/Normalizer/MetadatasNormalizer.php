<?php

namespace App\Serializer\Normalizer;

use App\Entity\Account;
use App\Entity\Client;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MetadatasNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $className = (new \ReflectionClass($object))->getShortName();
        $className = strtolower($className);


        $links = [
            "index" => [
                "url" => $this->urlGenerator->generate("api_" . $className  . "_index"),
                "method" => ["GET"]
            ],
            "show" => [
                'url' =>
                $this->urlGenerator->generate("app_" . $className . "_show", ["id" => $data['id']]),
                "method" => ["GET"]
            ],

            "new" => [
                'url' => $this->urlGenerator->generate("app_" . $className . "_new"),
                "method" => ["POST"]
            ],
            "edit" => [
                "url" => $this->urlGenerator->generate("app_" . $className . "_edit", ["id" => $data['id']]),
                "method" => ["PUT", "PATCH"]
            ],
            "delete" => [
                "url" => $this->urlGenerator->generate("app_" . $className . "_delete", ["id" => $data['id']]),
                "method" => ["DELETE"]
            ],

        ];

        $data['_links'] = $links;
        // TODO: add, edit, or delete some data

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        // TODO: return $data instanceof Object
        return $data instanceof Account || $data instanceof Client;
    }

    public function getSupportedTypes(?string $format): array
    {
        // TODO: return [Object::class => true];
        return [Account::class => true, Client::class => true];
    }
}
