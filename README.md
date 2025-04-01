<!-- markdownlint-disable MD013 -->
# Mezzio Order Tracker

This is a small, simplistic application, built with [Mezzio][mezzio-url] that shows how to build an order tracking system which is integrated with Twilio to provide notification functionality.

<!-- markdownlint-disable MD028 -->
> [!NOTE]
> Full credit goes to [the Australia Post website][auspost-url] and [the AusPost app][auspost-app-url] for the inspiration that I took from them.

> [!TIP]
> If you're not familiar with Mezzio, grab a copy of [Mezzio Essentials][mezzioessentials-url]. You'll get a comprehensive introduction to developing apps with Mezzio, with a minimum of background theory and concepts.
<!-- markdownlint-enable MD028 -->

## Prerequisites

To use this project, you need the following:

- Composer installed globally
- PHP 8.4.
  Earlier versions are **not** supported.
- Some familiarity with [Twig][twig-url]
- A Twilio account (free or paid).
  If you don't have an account already, [sign up for a free one][try-twilio-url].

## Getting Started

To get started with the application, run the following commands, wherever you build your PHP applications:

```bash
git clone git@github.com:settermjd/mezzio-order-tracker.git
cd mezzio-order-tracker
composer install
```

The commands clone the project locally, change into the project directory, and install PHP's dependencies.

Then, to start the application, run the following command.

```bash
composer serve
```

## Contributing

If you want to contribute to the project, whether you have found issues with it or just want to improve it, here's how:

- [Issues][issues-url]: ask questions and submit your feature requests, bug reports, etc
- [Pull requests][prs-url]: send your improvements

## Did You Find The Project Useful?

If the project was useful and you want to say thank you and/or support its active development, here's how:

- Add a GitHub Star to the project
- Write an interesting article about the project wherever you blog

## Disclaimer

No warranty expressed or implied. Software is as is.

[auspost-app-url]: https://auspost.com.au/about-us/about-our-site/australia-post-app
[auspost-url]: https://auspost.com.au/
[issues-url]: https://github.com/settermjd/mezzio-order-tracker/issues/new/choose
[mezzioessentials-url]: https://mezzioessentials.com
[mezzio-url]: https://docs.mezzio.dev/mezzio/
[prs-url]: https://github.com/settermjd/mezzio-order-tracker/pulls
[try-twilio-url]: https://twilio.com/try-twilio
[twig-url]: https://twig.symfony.com
<!-- markdownlint-enable MD013 -->
