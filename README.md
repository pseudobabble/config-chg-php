# Coding Challenge (Configuration)

Hi! If you're reading this, it's because you've been invited
to take a coding challenge for a backend engineering role at
Divido.

This particular challenge is focused around writing a parser
for configuration files; more details on the exact scenario
are below.

We anticipate that you will spend around 2 hours working on
this challenge. If you don't manage to complete everything in
time, that's ok; it's not a race.

## Scenario

For our (fictional) application to work correctly, we need to
be able to load one or more configuration files from disk and
merge them together. We have the following requirements:

  1. It must be possible to load multiple configuration files
     at the same time, and have later files override settings
     in earlier ones.

  2. It must be possible to retrieve parts of the configuration
     by a dot-separated path; this should work for both sections
     and for individual keys, no matter how deeply nested.

## Assumptions

1. All of the configuration files your code will process will
   be in JSON format.
   
2. Your code must be able to detect invalid JSON, and reject
   those configuration files. This should be handled via a
   custom exception.

3. Although there are libraries available for parsing configuration
   files in the way that we describe, we expect you not to use
   them; that would be defeating the purpose of this test.
   
4. You may use any syntax or functionality available in PHP 7.4 or
   below.

## Assessment Criteria

This repository contains a suite of tests (and sample configuration
files) that will exercise the requirements stated above. This test
suite uses PHPUnit, which we've preinstalled here.

Initially, all but one test will be disabled. We recommend that
you get this first test passing, then move on to the next.

You will be assessed both on the number of tests that pass, and on
the quality of the code that you write to pass them. We're looking
for concise, readable code.

The test suite can be executed by running `make test`. If you do
not have `make` installed, you can also run the tests directly
with `bin/phpunit`.
   
## Extra Credit

Alongside the main test suite, we've also supplied a suite of
extra credit tests.

These tests exercise additional functionality, such as support
for an additional configuration format (YAML). For these, you
may opt to use a third-party package, but you should be prepared
to explain your choice and what your criteria were for selecting
it.

It is optional to complete this portion of the challenge.
