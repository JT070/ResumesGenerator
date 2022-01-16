<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; //Para la validacion el title en Update
use Intervention\Image\Facades\Image; // Validacion de la imagen

class ResumeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resumes = auth()->user()->resumesRel;
        //return view('resume.index', ['resume' => $resume]);
        return view('resumes.index', compact('resumes')); // Lo mismo que la línea de arriba
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = 'test';
        return view('resumes.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // MANEJO DE ERRORES MANUAL [Caso: Mandar error si el usuario introduce un título repetido]
        // $resume = $user->resumesRel()->where('title', $request->title)->first();
        // if ($resume) {
        //     return back()
        //         ->withErrors(['title' => 'You already have a resume with this title.'])
        //         ->withInput(['title' => $request->title]);
        // }

        $resume = $user->resumesRel()->create([
            'title' => $request['title'],
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]);

        return redirect()->route('resumes.index')->with('alert', [
            'type' => 'primary',
            'message' => "Resume: '$resume->title' created sucessfully!",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resume  $resume
     * @return \Illuminate\Http\Response
     */
    public function show(Resume $resume)
    {
        return view('resumes.show', compact('resume'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resume  $resume
     * @return \Illuminate\Http\Response
     */
    public function edit(Resume $resume)
    {   
        $this->authorize('update', $resume);    
        // public funtion edit(Resume $request)
        //   $resume = auth()->user()->resumesRel()->where('id', $request->resume);
        //   $resume = Resume::where('id', $request->resume);
        return view('resumes.edit', compact('resume'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resume  $resume
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resume $resume)
    {
        // VALIDACIONES AUTOMÁTICAS
        $data = $request->validate([
            // 'name' => ['required', 'string'],  -> ES LO MISMO QUE ABAJO
            'name' => 'required|string',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'picture' => 'nullable|image',
            'about' => 'nullable|string',
            'title' => Rule::unique('resumes')
                // Para que ignore el que estamos editando (el que le pasamos) // Lo pasamos a FAF 
                ->where(fn ($query) => $query->where('user_id', $resume->user->id))
                ->ignore($resume->id) 
        ]);
        if (array_key_exists('picture', $data)) {
            $picture = $data['picture']->store('pictures', 'public');
            Image::make(public_path("storage/$picture"))->fit(800, 800);
            $data['picture'] = "/storage/$picture";
        }
        $resume->update($data);
        return redirect()->route('resumes.index')->with('alert', [
            'type' => 'success',
            'message' => "Resume: '$resume->title' updated sucessfully!",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resume  $resume
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resume $resume)
    {
        $this->authorize('delete', $resume);    
        $resume->delete();
        return redirect()->route('resumes.index')->with('alert', [
            'type' => 'danger',
            'message' => "Resume: '$resume->title' deleted sucessfully!",
        ]);
    }
}
