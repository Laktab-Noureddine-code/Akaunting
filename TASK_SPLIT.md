# Flutter App - API Routes Task Split

## Developer 1 — Auth & Core (Common)

| Module | Routes |
|---|---|
| **Auth (Login/Logout)** | `POST /logout`, `GET /profile` |
| **Ping** | `GET /ping` |
| **Users** | `GET /users/{user}/enable`, `GET /users/{user}/disable`, CRUD (`index`, `store`, `show`, `update`, `destroy`) |
| **Companies** | `GET /companies/{company}/owner`, `GET /companies/{company}/enable`, `GET /companies/{company}/disable`, CRUD |
| **Dashboards** | `GET /dashboards/{dashboard}/enable`, `GET /dashboards/{dashboard}/disable`, CRUD |

**~16 endpoints**

---

## Developer 2 — Products & Contacts

| Module | Routes |
|---|---|
| **Items** | `GET /items/{item}/enable`, `GET /items/{item}/disable`, CRUD |
| **Contacts** | `GET /contacts/{contact}/enable`, `GET /contacts/{contact}/disable`, CRUD |
| **Documents** | `GET /documents/{document}/received`, CRUD |
| **Document Transactions** | Nested CRUD under documents (`documents/{document}/transactions`) |

**~17 endpoints**

---

## Developer 3 — Banking & Finance

| Module | Routes |
|---|---|
| **Accounts** | `GET /accounts/{account}/enable`, `GET /accounts/{account}/disable`, CRUD |
| **Reconciliations** | CRUD |
| **Transactions** | CRUD |
| **Transfers** | CRUD |
| **Reports** | CRUD |

**~27 endpoints**

---

## Developer 4 — Settings & Configuration

| Module | Routes |
|---|---|
| **Categories** | `GET /categories/{category}/enable`, `GET /categories/{category}/disable`, CRUD |
| **Currencies** | `GET /currencies/{currency}/enable`, `GET /currencies/{currency}/disable`, CRUD |
| **Taxes** | `GET /taxes/{tax}/enable`, `GET /taxes/{tax}/disable`, CRUD |
| **Settings** | CRUD |
| **Translations** | `GET /translations/{locale}/all`, `GET /translations/{locale}/{file}` |

**~23 endpoints**

---

## Summary

| Developer | Domain | Modules |
|---|---|---|
| **Dev 1** | Auth & Core | Auth, Ping, Users, Companies, Dashboards |
| **Dev 2** | Products & Documents | Items, Contacts, Documents, Document Transactions |
| **Dev 3** | Banking & Finance | Accounts, Reconciliations, Transactions, Transfers, Reports |
| **Dev 4** | Settings & Config | Categories, Currencies, Taxes, Settings, Translations |

> Each `apiResource` generates 5 endpoints: `index`, `store`, `show`, `update`, `destroy`.
> Most modules also have `enable`/`disable` routes.
