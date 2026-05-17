# Submission Notes

## Candidate

Timothy Anthony

- Portfolio: https://timothyanthony.com
- GitHub: https://github.com/tebajanga

---

# Overview

I reviewed the application and focused mainly on refactoring, validation, security improvements, reducing duplicated logic, and improving maintainability while keeping:

- the same database structure
- the same functionality
- the same UI/UX

I tried not to change the behavior of the application outside of fixing issues and improving the code structure.

---

# Issues Found

1. Product price validation was missing
2. Negative prices could be entered
3. Uploaded product images could overwrite previous images
4. Unsafe image upload handling
5. Product updates used `$request->all()`
6. Product model did not define `$fillable`
7. Validation logic existed directly inside controllers
8. Product update logic was duplicated
9. Exchange rate handling used raw `curl`
10. Exchange rate exceptions were not handled properly
11. External API requests had no timeout
12. Delete product used `GET`
13. Logout used `GET`
14. `.env` file was committed into the repository
15. Important application flows were not tested
16. Inline styles existed inside Blade templates
17. Blade templates had duplicated layout structures
18. Admin routes needed better protection
19. Login rate limiting was missing
20. Database seeding could fail due to duplicate existing user records

---

# Main Changes

## Validation

Added Form Requests for:

- Login
- Add Product
- Update Product

This helped move validation logic out of the controllers and made the controllers cleaner.

Also added validation for:

- negative prices
- image type
- image size
- required fields

---

## Product Image Upload

The original upload logic was saving files using only the extension as the filename which caused uploaded images to overwrite previous ones.

I updated this to:

- generate unique filenames
- store files using Laravel Storage
- validate uploaded images

Images are now stored under:

```text
storage/app/public/uploads/products/
```

I also added a Product accessor for resolving image URLs.

This was mainly to keep compatibility with older images that already existed before the refactor while allowing newer uploads to use Laravel Storage properly.

---

## Product Update Logic

There was duplicated logic for:

- checking old/new prices
- dispatching email notifications

This logic existed in both:

- AdminController
- UpdateProduct command

I moved the logic into `ProductService` so both places reuse the same implementation.

---

## Exchange Rate Handling

The application was using raw `curl` calls.

I replaced this with Laravel HTTP Client and added:

- timeout handling
- exception handling
- caching
- fallback exchange rate

---

## Routes and Security

Updated routes to use:

- route model binding
- named routes
- middleware protection

Also changed:

- product delete from `GET` to `DELETE`
- logout from `GET` to `POST`

Added:

- login rate limiting
- session regeneration after login
- session invalidation on logout

---

## Mass Assignment

The Product model did not define `$fillable` fields and product updates were using `$request->all()`.

I replaced this with validated input only and added proper fillable fields.

---

## Blade/Layout Cleanup

There were repeated layouts and inline styles in multiple Blade files.

I extracted:

- shared layouts
- reusable partials

I also moved inline styles into the existing custom CSS file to reduce duplication and make the views easier to maintain.

The UI itself was not changed.

---

## Seeder Improvement

Re-seeding could fail because of duplicate existing user records.

I added logic to clear existing user records before seeding to make local testing and reseeding easier.

---

## Tests

Added feature tests for:

- homepage
- product details page
- login
- product update
- image upload
- invalid image upload
- negative price validation
- update product command

---

## Code Quality

Added:

- Laravel Pint
- Larastan / PHPStan

---

# Suggestions / Future Improvements

These are things I considered but did not implement because they would require database or architectural changes.

- Add pagination for product listings
- Add soft deletes for products
- Add audit logs
- Add role/permission management
- Migrate file storage to cloud (S3 or DigitalOcean Spaces)
- Store exchange rates in database
- Add CI/CD pipeline
- Add queue monitoring with Horizon
- Remove old unused product images automatically
- Add proper custom error pages for 404, 403, and 500 responses
- Add a public unique identifier for products instead of exposing incremental product IDs in URLs