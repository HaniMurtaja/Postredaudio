<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout\GalleryItem;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout\ParagraphItem;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout\TopSection;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout\Header;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout\SecondaryHeader;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout\Chapter;
use App\Nova\Flexible\Layouts\LayoutExtended;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;
use Spatie\MediaLibrary\HasMedia;
use App\Nova\Flexible\FlexibleExtended;
use Alexwenzel\DependencyContainer\HasDependencies;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class GeneralLayout extends LayoutExtended implements HasMedia
{
    use HasMediaLibrary;
    use HasDependencies;

    protected $name = 'general';
    protected $title = 'General Layout';
    public $textSectionWidth;
    protected $casts = [
        'header' => FlexibleCast::class,
        'paragraphs' => FlexibleCast::class,
        'top_section' => FlexibleCast::class,
        'gallery_items' => FlexibleCast::class,
    ];

    public function __construct($title = null, $name = null, $fields = null, $key = null, $attributes = [], callable $removeCallbackMethod = null, $model = null)
    {
        parent::__construct($title, $name, $fields, $key, $attributes, $removeCallbackMethod, $model);

        $this->textSectionWidth = $this->getTextSectionWidth();
    }

    public function getTextSectionWidth()
    {
        if ($this->model && $this->model->toFlexible($this->model->content)) {
            if ($this->model->toFlexible($this->model->content)->first()) {
                return $this->model->toFlexible($this->model->content)->first()->text_section_width;
            }
        }
    }

    public function fields()
    {
        return [
            Media::make('Background Media', 'background_media')
                ->tooltip(
                    'Column\'s background image or video.<br/>
                    Max size: 15 MB.<br/>
                    Allowed formats: <span class="postred-red">.jpg</span>, <span class="postred-red">.png</span>, <span class="postred-red">.avif</span>, <span class="postred-red">.webp</span>, <span class="postred-red">.webm</span>, <span class="postred-red">.mp4</span>.'
                )
                ->setAllowedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/avif', 'image/webp', 'video/mp4', 'video/webm'])
                ->conversionOnDetailView('minified')
                ->conversionOnIndexView('minified')
                ->conversionOnForm('minified')
                ->singleMediaRules('max:15000')
                ->rules(['max:1']),
            FlexibleExtended::make('Top Section', 'top_section')
                ->tooltip('Text that is displayed on top of the rest of the layout\'s content.')
                ->collapsed()
                ->addSingleLayout(TopSection::class)
                ->button('Add Top Section'),
            Select::make('Text Section Width', 'text_section_width')
                ->tooltip(
                    'How much horizontal space will the text content take.<br/>
                    If the layout has gallery items, the remaining space will be used by them.'
                )
                ->options([
                    '1/3' => '1/3 - One Third',
                    '2/3' => '2/3 - Two Thirds',
                    '3/3' => '3/3 - Three Thirds',
                    '1/2' => '1/2 - Half',
                ]),
            FlexibleExtended::make('Header', 'header')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(Header::class)
                ->button('Add Header'),
            FlexibleExtended::make('Seondary Header', 'secondary_header')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(SecondaryHeader::class)
                ->button('Add Secondary Header'),
            FlexibleExtended::make('Chapter', 'chapter')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(Chapter::class)
                ->button('Add Chapter'),
            FlexibleExtended::make('Paragraphs', 'paragraphs')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(ParagraphItem::class)
                ->button('Add Paragraph item'),
            FlexibleExtended::make('Gallery Items', 'gallery_items')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(GalleryItem::class)
                ->button('Add Gallery Item'),
            Select::make('Gallery Slide Title Size', 'gallery_title_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Number::make('Slide Change Speed', 'gallery_speed')
                ->tooltip(
                    'Time between slide changes.<br>
                    Default: 1 second.'
                )
                ->defaultValue(1)
                ->min(0.01)
                ->max(120)
                ->step(0.01)
        ];
    }
}
