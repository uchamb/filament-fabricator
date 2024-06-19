<?php

namespace Z3d0X\FilamentFabricator\Concerns;

trait EnablesDataMutation {
  protected function mutateFormDataBeforeFill(array $data): array
  {
    foreach($data['blocks'] ?? [] as $index => $block) {
      $className = $this->getPageBlockClass($block['type']);
      if(method_exists($className, 'mutateDataBeforeFill')) {
        $data['blocks'][$index]['data'] = $className::mutateDataBeforeFill($block['data']);
      }
    }
    return $data;
  }

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    foreach($data['blocks'] ?? [] as $index => $block) {
      $className = $this->getPageBlockClass($block['type']);
      if(method_exists($className, 'mutateFormDataBeforeSave')) {
        $data['blocks'][$index]['data'] = $className::mutateFormDataBeforeSave($block['data']);
      }
    }
    return $data;
  }

  protected function mutateFormDataBeforeSave(array $data): array
  {
    foreach($data['blocks'] ?? [] as $index => $block) {
      $className = $this->getPageBlockClass($block['type']);
      if(method_exists($className, 'mutateFormDataBeforeSave')) {
        $data['blocks'][$index]['data'] = $className::mutateFormDataBeforeSave($block['data']);
      }
    }
    return $data;
  }

  private function getPageBlockClass($string): string
  {
    $words = explode('-', $string);
    $className = '';
    foreach ($words as $word) {
      $className .= ucfirst($word);
    }
    return '\App\Filament\Fabricator\PageBlocks\\'.$className;
  }
}