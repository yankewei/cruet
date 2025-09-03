<?php

declare(strict_types=1);

namespace Ykw\Cruet\StringOp;

/**
 * Constants used by string operations, ported from Rust cruet library
 */
class Constants
{
    /**
     * List of uncountable words that should not be pluralized
     * Ported from Rust cruet constants
     */
    public const UNCOUNTABLE_WORDS = [
        "advice", "aircraft", "art", "bison", "blood", "bream", "butter", "cash", "clothing", "cod",
        "commerce", "core", "deer", "economics", "equipment", "fish", "folklore", "food", "furniture", 
        "gold", "health", "help", "homework", "honey", "impatience", "information", "jeans", "knowledge",
        "love", "luck", "mathematics", "molasses", "music", "news", "noodle", "nutrition", "progress",
        "rain", "research", "rice", "sand", "series", "sheep", "software", "species", "sugar", "traffic",
        "understanding", "underwear", "vapor", "water", "wood", "wool", "work", "aircraft", "aluminum",
        "aviation", "brass", "breeches", "carp", "clothing", "coal", "data", "deer", "fish", "flour", "fruit",
        "furniture", "golf", "graffiti", "headquarters", "homework", "housework", "ice", "information",
        "jewelry", "kin", "leather", "linguistics", "luggage", "mathematics", "means", "moose", "music", "news",
        "research", "rice", "salmon", "series", "sheep", "species", "sugar", "swine", "trout", "tuna", "whiting",
        "wildebeest", "wisdom", "you", "zebra", "pokemon", "deer", "sheep", "fish", "moose", "aircraft", "species",
        "headquarters", "information", "rice", "money", "equipment", "vocabulary", "series", "coffee", "police",
        "personnel", "staff", "advice", "energy", "excretion", "digestion", "cooperation", "health", "justice", "labour",
        "machinery", "metadata", "peace", "rampage", "salvation", "statistics", "stealth", "unreason", "victory",
        "knowledge", "love", "marriage", "music", "noise", "research", "work", "advice", "air", "art", "artw", "aircraft",
        "aluminum", "information", "bison", "blood", "bream", "butter", "cash", "clothing", "cod", "commerce", "core",
        "deer", "economics", "equipment", "fish", "folklore", "food", "furniture", "gold", "health", "homework", "honey",
        "impatience", "jeans", "knowledge", "love", "luck", "mathematics", "molasses", "news", "noodle", "nutrition",
        "progress", "rain", "research", "rice", "sand", "series", "sheep", "software", "species", "sugar", "traffic",
        "understanding", "underwear", "vapor", "water", "wood", "wool", "work", "breeches", "carp", "coal", "data", 
        "flour", "fruit", "golf", "graffiti", "headquarters", "housework", "ice", "jewelry", "kin", "leather", 
        "linguistics", "luggage", "means", "salmon", "swine", "trout", "tuna", "whiting", "wildebeest", "wisdom",
        "zebra", "pokemon", "money", "vocabulary", "coffee", "police", "personnel", "staff", "energy", "excretion",
        "digestion", "cooperation", "justice", "labour", "machinery", "metadata", "peace", "rampage", "salvation",
        "statistics", "stealth", "unreason", "victory", "marriage", "noise", "artw"
    ];
}