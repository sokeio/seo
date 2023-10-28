<?php

namespace BytePlatform\Seo;

use BytePlatform\Seo\Schemas\ArticleSchema;
use BytePlatform\Seo\Schemas\BreadcrumbListSchema;
use BytePlatform\Seo\Schemas\Schema;
use Closure;
use Illuminate\Support\Collection;

class SchemaCollection extends Collection
{
    protected array $dictionary = [
        'article' => ArticleSchema::class,
        'breadcrumbs' => BreadcrumbListSchema::class,
    ];

    public array $markup = [];

    public function addArticle(Closure $builder = null): static
    {
        $this->markup[$this->dictionary['article']][] = $builder ?: fn (Schema $schema): Schema => $schema;

        return $this;
    }

    public function addBreadcrumbs(Closure $builder = null): static
    {
        $this->markup[$this->dictionary['breadcrumbs']][] = $builder ?: fn (Schema $schema): Schema => $schema;

        return $this;
    }

    public static function initialize(): static
    {
        return new static();
    }
}
