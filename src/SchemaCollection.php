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
    public function addClass($class, Closure $builder = null): static
    {
        $this->markup[$class][] = $builder ?: fn (Schema $schema): Schema => $schema;

        return $this;
    }
    public function addArticle(Closure $builder = null): static
    {
        return $this->addClass($this->dictionary['article'], $builder);
    }

    public function addBreadcrumbs(Closure $builder = null): static
    {
        return $this->addClass($this->dictionary['breadcrumbs'], $builder);
    }

    public static function initialize(): static
    {
        return new static();
    }
}
