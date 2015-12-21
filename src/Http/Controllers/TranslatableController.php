<?php

namespace AbbyLynn\Translatable\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use AbbyLynn\Translatable\Services\LangFiles;
use AbbyLynn\Translatable\Services\LangNames;
use AbbyLynn\Translatable\Models\Language;
use AbbyLynn\Translatable\Http\Requests\LanguageRequest;

class TranslatableController extends Controller
{

    private $langFiles;
    private $language;
    private $langNames;

    public function __construct() {
        $this->langFiles = new LangFiles;
        $this->language = new Language;
        $this->langNames = new LangNames;
    }

    /**
     * Display a listing of the current langauges.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageData = [
            'languages' => $this->language->orderBy('created_at')->get()
        ];

        return view('translatable.translations', $pageData);
    }

    /**
     * Load New Languages from 'resources/lang'
     * 
     * @return \Illuminate\Http\Response
     */
    public function load() {
        $folders = array_diff(scandir(base_path('resources/lang')), array(".", ".."));

        $count = 0;

        foreach($folders as $iso) {
            if(!$this->language->abbr($iso)->first()) {
                $lang = new $this->language;
                $lang->name = $this->langNames->languageByCode1($iso);
                $lang->abbr = $iso;
                $lang->native = $this->langNames->nativeByCode1($iso);
                $lang->active = 1;
                $lang->default = 0;
                $lang->save();

                $count++;
            }
        }

        if($count) {
            $status = $count.' new langauges have been found and imported.';
        } else {
            $status = 'No new languages have been found.';
        }

        return redirect()->back()->with('status', $status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LanguageRequest $request)
    {
        $active = false;
        $default = false;

        $lang = $this->language->find($request->get('id'));
        $lang->name = $request->get('name');
        $lang->native = $request->get('native');
        $lang->abbr = $request->get('abbr');

        if($request->has('active')) {
            $lang->active = 1;
        }

        if($request->has('default')) {
            $lang->default = 1;
        }

        $lang->save();

        return redirect()->back()->with('status', 'The '.$lang->name.' language settings have been updated.');

    }

    /**
     * Show translations for specified language
     * 
     * @param  string $abbr 
     * @param  string $file 
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($abbr, $file = null) {
        $lang = $this->language->abbr($abbr)->first();
        $defaultLang = $this->language->where('default', 1)->first();

        $languageFiles = $this->langFiles->getlangFiles();

        if(is_null($file)) {
            foreach($languageFiles as $fileFound) {
                $file = strtolower($fileFound->name);
                if (!in_array($file, config('translatable.ignore'))) {
                    $fileFound->active = true;
                    break;
                }
            }
        }

        if (in_array($file, config('translatable.ignore'))) {
            return redirect()->to('languages')->with('danger', 'That language file cannot be edited online.');
        }


        $this->langFiles->setLanguage($lang->abbr);
        $this->langFiles->setFile($file);

        $pageData = [
            'currentFile'   => $file,
            'currentLang'   => ['name' => $lang->name, 'abbr' => $lang->abbr],

            'langFiles'     => $languageFiles,
            'fileArray'     => $this->langFiles->getFileContent(),
        ];

        $this->langFiles->setLanguage($defaultLang->abbr);
        $pageData['defaultLang'] = ['name' => $defaultLang->name, 'abbr' => $defaultLang->abbr];
        $pageData['defaultArray'] = $this->langFiles->getFileContent();

        return view('translatable.edit', $pageData);
    }

    /**
     * Update translation file
     * 
     * @param  Request $request
     * @param  string  $abbr
     * @param  string  $file
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $abbr, $file) {
        $lang = $this->language->abbr($abbr)->first();

        if (in_array($file, config('translatable.ignore'))) {
            return redirect()->to('languages')->with('danger', 'That language file cannot be edited online.');
        }

        $this->langFiles->setLanguage($lang->abbr);

        $this->langFiles->setFile($file);

        if($this->langFiles->setFileContent($request->except('_token'))) {
            return redirect()->to('languages')->with('status', 'Your language translations have successfully been updated.');
        }

        return redirect()->back()->with('danger', 'Whoops, something went wrong when saving those translations.');
    }

    /**
     * Delete from database and language files
     * 
     * @param  string $abbr
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($abbr) {
        $lang = $this->language->abbr($abbr)->first();

        $lang->delete();

        rmdir(base_path('resources/lang/'.$abbr));

        return redirect()->back()->with('status', 'The '.$lang->name.' language has successfully been deleted via database and file storage.');
    }

    /**
     * Modify The Status of the language
     * @param  string $abbr
     * @param  string $status [active|disable|defaulted]
     * @return \Illuminate\Http\Response
     */
    public function activeStatus($abbr, $status)
    {
        $lang = $this->language->abbr($abbr)->first();
        $default = false;

        $newStatus = 1;
        $ending = 'activated';

        if($status == 'defaulted') {

            if($curDefault = $this->language->where('default', 1)->first()) {
                $curDefault->default = 0;
                $curDefault->save();
            }

            $lang->default = 1;

            $default = true;
            $status = 'active';

        }
            
        if($status == 'disable') {

            if($lang->default) {
                $curDefault = $this->language->orderBy('created_at')->first();
                $curDefault->active = 1;
                $curDefault->default = 1;
                $curDefault->save();

                $lang->default = 0;
            }

            $newStatus = 0;
            $ending = 'disabled';
        }

        if($default) {
            $ending = 'set as the default language.';
        }

        $lang->active = $newStatus;
        $lang->save();

        $message = $lang->name.' has been successfully '.$ending;
        
        return redirect()->back()->with('status', $message);
    }
}
