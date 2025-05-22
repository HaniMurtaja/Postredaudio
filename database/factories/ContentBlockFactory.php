<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ColorSCheme as Color;
use App\Models\Department;
use App\Models\Testimonial;
use App\Enums\LayoutType;
use App\Enums\ColorScheme;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ContentBlockFactory extends Factory
{
    protected $layoutData;
    protected $colorValues;
    protected $layoutTypes;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->colorValues = ColorScheme::values();
        $this->layoutTypes = LayoutType::values();

        return [
            "color_scheme_id" => Color::where("name", $this->colorValues[array_rand($this->colorValues)])->value('id'),
            "section_name" => rand(0, 1) ? null : fake()->sentence(rand(1, 3)),
            "content" => json_encode([$this->generateLayout($this->layoutData["layout_type"] ?? $this->layoutTypes[array_rand($this->layoutTypes)], $this->layoutData["content"] ?? null)], true),
        ];
    }

    public function layoutData($data)
    {
        $this->layoutData = $data;

        return $this;
    }

    protected function generateLayout($layoutType, $layoutData = null)
    {
        if (in_array($layoutType, $this->layoutTypes)) {
            switch ($layoutType) {
                case "client":
                    return $this->clientLayout($layoutData);
                    break;
                case "column":
                    return $this->columnLayout($layoutData);
                    break;
                case "department":
                    return $this->departmentLayout($layoutData);
                    break;
                case "testimonial":
                    return $this->testimonialLayout($layoutData);
                    break;
                case "general":
                    return $this->generalLayout($layoutData);
                    break;
                case "header":
                    return $this->headerLayout($layoutData);
                    break;
                case "slider":
                    return $this->sliderLayout($layoutData);
                    break;
                case "switch":
                    return $this->switchLayout($layoutData);
                    break;
                case "text":
                    return $this->textLayout($layoutData);
                    break;
                default:
                    return null;
            }
        }
    }

    protected function clientLayout()
    {
        return $this->generateFlexibleLayout("client");
    }

    protected function columnLayout($data)
    {
        $content = [
            "background_media" => $data["background_media"],
            "columns" => array_map(fn ($column) => $this->generateFlexibleLayout("column_layout_column", $column), $data["columns"] ?? [])
        ];

        return $this->generateFlexibleLayout("column", $content);
    }

    protected function departmentLayout($data)
    {
        $content = [
            "departments" => array_map(fn ($departmentName) => $this->generateFlexibleLayout("department_layout_single_department", ["department" => Department::where('name', $departmentName)->value('id')]), $data["departments"] ?? [])
        ];

        return $this->generateFlexibleLayout("department", $content);
    }

    protected function generalLayout($data)
    {
        $content = [
            "background_media" => $data["background_media"],
            "text_section_width" => $data["text_section_width"] ?? "3/3",
            "top_section" => $data["top_section"] ? [$this->generateFlexibleLayout("general_layout_top_section", $data["top_section"])] : null,
            "header" => $data["header"] ? [$this->generateFlexibleLayout("general_layout_header", $data["header"])] : null,
            "secondary_header" => $data["secondary_header"] ? [$this->generateFlexibleLayout("general_layout_secondary_header", $data["secondary_header"])] : null,
            "chapter" => $data["chapter"] ? [$this->generateFlexibleLayout("general_layout_chapter", $data["chapter"])] : null,
            "paragraphs" => $data["paragraphs"] ? array_map(fn ($paragraph) => $this->generateFlexibleLayout("general_layout_paragraph_item", $paragraph), $data["paragraphs"]) : [],
            "gallery_items" => $data["gallery_items"] ? array_map(fn ($galleryItem) => $this->generateFlexibleLayout("general_layout_gallery_item", $galleryItem), $data["gallery_items"]) : [],
            "gallery_title_size" => $data["gallery_title_size"] ?? "medium"
        ];

        return $this->generateFlexibleLayout("general", $content);
    }

    protected function headerLayout($data)
    {
        $content = [
            "background_media" => $data["background_media"],
            "headers" => array_map(fn ($header) => $this->generateFlexibleLayout("single_header", [
                "header_text" => $header["header_text"],
                "subheaders" => array_map(fn ($subheader) => $this->generateFlexibleLayout("single_subheader", ["subheader_text" => $subheader]), $header['subheaders'])
            ]), $data["headers"] ?? [])
        ];

        return $this->generateFlexibleLayout("header", $content);
    }

    protected function testimonialLayout($data)
    {
        $testimonials = Testimonial::whereIn('name', $data['testimonials'])->pluck('id');

        $testimonials = array_map(function ($testimonialId) {
            return $this->generateFlexibleLayout('testimonial_layout_single_testimonial', ['testimonial' => $testimonialId]);
        }, $testimonials->toArray());

        $content = [
            'title' => '',
            'testimonials' => $testimonials
        ];

        return $this->generateFlexibleLayout('testimonial', $content);
    }

    protected function sliderLayout($data)
    {
        $content = [
            'slide_width' => $data['slide_width'] ?? '100',
            'title_text_size' => $data['title_text_size'] ?? 'medium',
            'slides' => array_map(fn ($slide) => $this->generateFlexibleLayout('slider_layout_slide', $slide), $data['slides'] ?? [])
        ];

        return $this->generateFlexibleLayout('slider', $content);
    }

    protected function switchLayout($data)
    {
        $content = [
            "switch_values" => array_map(fn ($switchValue) => $this->generateFlexibleLayout("switch_layout_switch_value", $switchValue), $data["switch_values"] ?? [])
        ];

        return $this->generateFlexibleLayout("switch", $content);
    }

    protected function textLayout($data)
    {
        $content = [
            "background_media" => $data['background_media'],
            "images" => $data['images'] ?? null,
            "left_column" => $data["left_column"] ? [$this->generateFlexibleLayout("text_layout_left_column", [
                "headers" => array_map(fn ($header) => $this->generateFlexibleLayout("text_layout_header_item", $header), $data["left_column"]["headers"] ?? []),
                "header_position" => $data["left_column"]["header_position"] ?? null,
                "caption" => $data['left_column']["caption"] ?? null,
                "caption_size" => $data['left_column']["caption_size"] ?? null,
                "caption_style" => $data['left_column']["caption_style"] ?? null,
                "description" => $data['left_column']["description"] ?? null,
                "description_size" => $data['left_column']["description_size"] ?? null,
                "description_style" => $data['left_column']["description_style"] ?? null
            ])] : null,
            "right_column" => $data["right_column"] ? [
                $this->generateFlexibleLayout("text_layout_right_column", [
                    "paragraphs" => array_map(fn ($paragraph) => $this->generateFlexibleLayout("text_layout_paragraph_item", $paragraph), $data["right_column"]["paragraphs"] ?? [])
                ])
            ] : null
        ];

        return $this->generateFlexibleLayout("text", $content);
    }


    protected function generateFlexibleLayout($layoutName, $content = null)
    {
        return [
            "key" => generateRandomString(),
            "layout" => $layoutName,
            "attributes" => $content
        ];
    }
}
