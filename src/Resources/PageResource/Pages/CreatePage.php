<?php

namespace Z3d0X\FilamentFabricator\Resources\PageResource\Pages;

use App\Filament\Actions\Forms\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Z3d0X\FilamentFabricator\Concerns\EnablesDataMutation;
use Z3d0X\FilamentFabricator\Resources\PageResource;
use App\Concerns\CreateRecord\Translatable;

class CreatePage extends CreateRecord
{
  use Concerns\HasPreviewModal, Translatable, EnablesDataMutation;

  protected static string $resource = PageResource::class;

  public static function getResource(): string
  {
    return config('filament-fabricator.page-resource') ?? static::$resource;
  }

  protected function getActions(): array
  {
    return [
      LocaleSwitcher::make(),
      PreviewAction::make(),
    ];
  }
}
