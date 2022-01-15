<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; //Para la validacion el title en Update

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

        return redirect()->route('resumes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resume  $resume
     * @return \Illuminate\Http\Response
     */
    public function show(Resume $resume)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resume  $resume
     * @return \Illuminate\Http\Response
     */
    public function edit(Resume $resume)
    {   // public funtion edit(Resume $request)
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
        $request->validate([
            // 'name' => ['required', 'string'],  -> ES LO MISMO QUE ABAJO
            'name' => 'required|string',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'picture' => 'nullable|image',
            'about' => 'nullable|string',
            'title' => Rule::unique('resumes')->where(function ($query) use ($resume) {
                return $query->where('user_id', $resume->user->id);
            })->ignore($resume->id) // Para que ignore el que estamos editando (el que le pasamos)
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
        //
    }
}
