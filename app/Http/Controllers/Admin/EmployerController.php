<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployerController extends Controller
{
    public function index(): View
    {
        return view('admin.employers.index', [
            'employers' => Employer::withCount('jobs')->orderBy('verified')->orderBy('name')->orderBy('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.employers.form', ['employer' => new Employer()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Employer::create($this->validated($request));

        return redirect()->route('admin.employers.index')->with('status', 'Employer created.');
    }

    public function edit(Employer $employer): View
    {
        return view('admin.employers.form', compact('employer'));
    }

    public function update(Request $request, Employer $employer): RedirectResponse
    {
        $employer->update($this->validated($request, $employer));

        return redirect()->route('admin.employers.index')->with('status', 'Employer updated.');
    }

    public function destroy(Employer $employer): RedirectResponse
    {
        if ($employer->jobs()->exists()) {
            return back()->withErrors(['employer' => 'Cannot delete an employer with job listings.']);
        }

        $employer->delete();

        return back()->with('status', 'Employer deleted.');
    }

    private function validated(Request $request, ?Employer $employer = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('employers', 'slug')->ignore($employer?->id)],
            'website' => ['nullable', 'url', 'starts_with:https://', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
            'location' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'verified' => ['sometimes', 'boolean'],
        ]);

        // The form sends an explicit 0 or 1. An omitted field must not
        // silently change the review status of an existing employer.
        if ($request->exists('verified')) {
            $data['verified'] = $request->boolean('verified');
        } elseif ($employer === null) {
            $data['verified'] = false;
        }

        return $data;
    }
}
