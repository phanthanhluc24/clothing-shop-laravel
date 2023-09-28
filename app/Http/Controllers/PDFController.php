<?php

namespace App\Http\Controllers;

use App\Models\Pdf_Cv;
use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Str;

class PDFController extends Controller
{
    public function convertPdfToText()
    {
        $text = Pdf::getText(public_path('24A_Phan Thanh Luc.pdf'), 'C:\Program Files\Git\mingw64\bin\pdftotext.exe');

        return response()->json(mb_convert_encoding(preg_replace("/\r/", "\n", $text), 'UTF-8', 'auto'));
    }

    public function storePdf(Request $request)
    {
        $pdf = $request->pdf;
        if ($request->hasFile("pdf")) {
            $path = $pdf->store("public");
        }
        $pdfPath = storage_path("app/public/" . str_replace('public/', '', $path));
        $text = Pdf::getText($pdfPath, "C:\Program Files\Git\mingw64\bin\pdftotext.exe");

        $keywords = ["EDUCATION", "LANGUAGE", "EXPERIENCE", "HOBBIES", "SKILL", "OBJECTIVES"];

        $sections = preg_split("/\s*\n\s*(?=[A-Z])/", $text);
        $foundSections = [];
        $currentKeywork = null;

        foreach ($sections as $section) {
            $section = iconv('UTF-8', 'UTF-8//IGNORE', $section);
            $mathchingKeywork = collect($keywords)->first(function ($keyword) use ($section) {
                return str_contains($section, $keyword);
            });

            if ($mathchingKeywork) {
                $currentKeywork = $mathchingKeywork;
                $foundSections[$currentKeywork] = "";
            } elseif ($currentKeywork) {
                $foundSections[$currentKeywork] .= "\n" . $section;
            }
        }


        $educations = $foundSections["EDUCATION"] ?? "";
        $language = $foundSections["LANGUAGE"] ?? "";
        $experience = $foundSections["WORK EXPERIENCE"] ?? "";
        $hobbies = $foundSections["HOBBIES"] ?? "";
        $skill = $foundSections["SKILL"] ?? "";
        $objectives = $foundSections["OBJECTIVES"] ?? "";

        $full_cv = new Pdf_Cv();
        $full_cv->educations = $educations;
        $full_cv->hobbies = $hobbies;
        $full_cv->language = $language;
        $full_cv->experience = $experience;
        $full_cv->skill = $skill;
        $full_cv->objectives = $objectives;
        $full_cv->save();
        return response()->json(
            [
                "status" => 200,
                "Doc cv thanh cong"
            ]
        );
    }


    public function getCv(){

        $pdf_cv=Pdf_Cv::all();

        return response()->json($pdf_cv);
    }
}
